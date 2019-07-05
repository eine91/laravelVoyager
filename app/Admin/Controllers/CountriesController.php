<?php

namespace App\Admin\Controllers;

use App\Admin\Models\Country;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Admin;

class CountriesController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('Index')
            ->description('description')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Country);

        $grid->country_code('Code');
        $grid->country_name('Country');

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Country::findOrFail($id));



        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Country);


        $form->tab('Country Info',function($form){

            $form->multipleSelect('country_code', 'Country')->options(['1' => 'Monday', '2' => 'Tuesday', '3' => 'Wednesday', '4' => 'Thursday', '5' => 'Friday', '6' => 'Saturday', '0' => 'Sunday']);

            $form->multipleImage('contacts', 'Images')->path('/storage/ImagesCountries');


        })->tab('City Info',function($form){

                           $form->text('city_code','City Code')->setWidth(2);


        });


        Admin::js('/js/admin/countryController.js');

        return $form;
    }

    public function store()
    {
        $validation = request()->validate([


        ]);

        if(request()->hasfile('contacts'))
        {

            $fileLocations = [];
            foreach(request()->file('contacts') as $image)
            {
                $filenameWithExt = $image->getClientOriginalName();
                // Get just filename
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                // Get just ext
                $extension = $image->getClientOriginalExtension();
                // Filename to store
                $fileNameToStore= $filename.'_'.time().'.'.$extension;
                // Upload Image

                $year = gmdate('Y');

                $month = gmdate('m');

                $path = $image->storeAs("public/ImagesCountries/$year/$month", $fileNameToStore);

                $fileLocations[] = "storage/ImagesCountries/$year/$month/".$fileNameToStore;
            }

            $validatedData['contacts'] = $fileLocations;
        }

        $validatedData['country_code'] = '1';

        $validatedData['country_name'] = 'India';
        
        Country::create($validatedData);

    }
}
