<?php

namespace BlueStorm\Imports\Controllers;
use BlueStorm\Imports\Models\Import;
use BlueStorm\Imports\Repositories\ImportRepository;
use App\Http\Controllers\Controller;
use BlueStorm\Imports\Requests\ImportRequest;
use Illuminate\Http\Request;
use Statamic\Fields\BlueprintRepository;
use Statamic\Facades\Blueprint;
use Statamic\Assets\Asset;
use Statamic\Facades\Collection;
use Statamic\Facades\Entry;
use Statamic\CP\Breadcrumbs;

/**
 * Class ImporterCPController
 *
 * @package BlueStorm\Imports\Controllers
 */
class ImportsCPController extends Controller
{
    private $importRepository;
    private $blueprintRepository;

    /**
     * ImportsCPController constructor.
     * @param ImportRepository $importRepository
     * @param BlueprintRepository $blueprintRepository
     */
    public function __construct(ImportRepository $importRepository, BlueprintRepository $blueprintRepository)
    {
        $this->importRepository = $importRepository;
        $this->blueprintRepository = $blueprintRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $imports = $this->importRepository->getAll();
        return view('imports::cp.index', compact('imports'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $crumbs = Breadcrumbs::make([
            ['text' => __('< Imports'), 'url' => cp_route('imports.index')],
        ]);

        $values = [];
        $blueprint = $this->getBlueprint();
        $fields = $blueprint->fields()->addValues($values)->preProcess();

        return view('imports::cp.new', [
            'blueprint' => $blueprint->toPublishArray(),
            'values'    => $fields->values(),
            'meta'      => $fields->meta(),
            'crumbs'    => $crumbs,
        ]);
    }

    /**
     * @param ImportRequest $request
     * @return bool
     */
    public function store(ImportRequest $request)
    {
        return $this->importRepository->store($request->input());
    }

    /**
     * @param Import $import
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Import $import)
    {
        $crumbs = Breadcrumbs::make([
            ['text' => __('< Imports'), 'url' => cp_route('imports.index')],
        ]);

        $values = [
            'name'             => $import->name,
            'file'             => $import->file,
            'collectionHandle' => $import->collectionHandle,
        ];

        $blueprint = $this->getBlueprint();
        $fields = $blueprint->fields()->addValues($values)->preProcess();

        return view('imports::cp.edit', [
            'blueprint' => $blueprint->toPublishArray(),
            'values'    => $fields->values(),
            'meta'      => $fields->meta(),
            'import'    => $import,
            'crumbs'    => $crumbs,
        ]);
    }

    /**
     * @param ImportRequest $request
     * @param Import $import
     * @return mixed
     */
    public function update(ImportRequest $request, Import $import)
    {
        return $this->importRepository->update($import, $request->input());
    }

    /**
     * @param Import $import
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function mapFields(Import $import)
    {
        $fieldMapping = $import->fieldMapping;

        $crumbs = Breadcrumbs::make([
            [
                'text' => __('< Imports'),
                'url' => cp_route('imports.index')
            ],
        ]);

        $path = Asset::find($import->file)->resolvedPath();
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        if($extension == 'xml')
        {
            $xmlString = file_get_contents($path);
            $data = $this->xmlToArray($xmlString);
        }
        else if ($extension = 'json')
        {
            $jsonString = file_get_contents($path);
            $data = $this->jsonToArray($jsonString);
        }
        else
        {
            $data = array_map('str_getcsv', file($path));
        }

        $dataHeaders = array_slice($data, 0, 1);

        $headers = array();
        foreach ($dataHeaders as $headerValues)
        {
            foreach ($headerValues as $header)
            {
                $headers[] = trim($header);
            }
        }

        $blueprint = "collections.". $import->collectionHandle.".".$import->collectionHandle;
        $collection = $this->blueprintRepository->find($blueprint)->contents();

        $collectionFields = array();

        foreach ($collection['sections']['main']['fields'] as $field)
        {
            $collectionFields[] = $field['handle'];
        }

        return view('imports::cp.map',  [
            'fieldMapping'     => $fieldMapping,
            'collectionFields' => $collectionFields,
            'import'           => $import,
            'headers'          => $headers,
            'crumbs'           => $crumbs,
        ]);
    }

    /**
     * @param Request $request
     * @param Import $import
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeMappedFields(Request $request, Import $import)
    {
        $request->merge([
            $import->fieldUnique = $request['fieldUnique'],
        ]);

        if ($this->importRepository->storeMappedFields($import, $request->except('_token', 'fieldUnique')))
        {
            return redirect()->back()->with('success', 'Field mappings saved successfully');
        }

        return redirect()->back()->with('success', 'There was an error saving field mappings');
    }

    /**
     * @param Import $import
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Import $import)
    {
        $path = Asset::find($import->file)->resolvedPath();
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        if($extension == 'xml')
        {
            $xmlString = file_get_contents($path);
            $data = $this->xmlToArray($xmlString);
        }
        else if ($extension = 'json')
        {
            $jsonString = file_get_contents($path);
            $data = $this->jsonToArray($jsonString);
        }
        else
        {
            $data = array_map('str_getcsv', file($path));
        }

        $header = array_shift($data);

        foreach ($data as $key => $value) {
            $data[$key] = array_combine($header, $value);
        }

        $fieldMapping = $import->fieldMapping;

        $mappedData = collect($data)->map(function ($entry) use ($fieldMapping) {
            $map = array();

            foreach ($fieldMapping as $key => $value) {
                if (array_key_exists($value, $entry)) {
                    $map[$key] = $entry[$value];
                }
            }

            return $map;
        });

        foreach ($mappedData as $item)
        {
            $entry = Collection::find($import->collectionHandle)->queryEntries()->where($import->fieldUnique, $item[$import->fieldUnique])->first();

            if (!$entry) {
                $entry = Entry::make()->published(true)->data($item)->slug($item['title'])->collection($import->collectionHandle);
            } else {

                foreach ($item as $key => $value)
                {
                    $entry->set($key, $value);
                }
            }

            $entry->save();
        }

        return redirect()->back()->with('success', 'Import successful');
    }

    /**
     * @param Import $import
     * @return mixed
     */
    public function destroy(Import $import)
    {
        $this->importRepository->destroy($import);
        return redirect()->back()->with('success', 'Deleted successful');
    }

    /**
     * @return \Statamic\Fields\Blueprint
     */
    private function getBlueprint()
    {
        $contents = [
            'name' => [
                'display' => 'Import Name',
                'type' => 'text',
                'validate' => 'required',
                'width' => 100,
            ],
            'file' => [
                'display' => 'CSV / XML / JSON',
                'type' => 'assets',
                'max_files' => 1,
                'validate' => 'required',
                'width' => 100,
            ],
            'collectionHandle' => [
                'display' => 'Collections',
                'type' => 'collections',
                'mode' => 'select',
                'max_items' => 1,
                'default_value' => 'Choose...',
                'validate' => 'required',
                'width' => 100,
            ],
        ];

        return Blueprint::makeFromFields($contents);
    }

    /**
     * @param $xmlstring
     * @return mixed
     */
    private function xmlToArray($xmlString)
    {

        $xml = simplexml_load_string($xmlString, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);

        $newArray = array();
        $keys = array();
        $headers = array();

        foreach ($array as $item)
        {
            foreach ($item as $value)
            {
                $newArray[] = $value;

                foreach ($value as $key => $val)
                {
                    $keys[] = $key;
                }
            }
        }

        $keys = array_unique($keys);
        $headers[] = $keys;

        $resultsArray = array_merge($headers, $newArray);

        return $resultsArray;
    }

    /**
     * @param $jsonstring
     */
    private function jsonToArray($jsonString)
    {
        $array = json_decode($jsonString, true);

        $newArray = array();
        $keys = array();
        $headers = array();

        foreach ($array as $item)
        {
            $newArray[] = $item;

            foreach ($item as $key => $val)
            {
                $keys[] = $key;
            }
        }

        $keys = array_unique($keys);
        $headers[] = $keys;

        $resultsArray = array_merge($headers, $newArray);

        return $resultsArray;
    }
}
