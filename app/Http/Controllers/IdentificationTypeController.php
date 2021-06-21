<?php

namespace App\Http\Controllers;

use App\Repositories\IdentificationTypeRepository;
use Illuminate\Http\Request;

class IdentificationTypeController extends Controller
{


    /**
     * @var IdentificationTypeRepository
     */
    protected $identificationTypeRepository;

    /**
     * UsersController constructor.
     * @param IdentificationTypeRepository $identificationTypeRepository
     */
    public function __construct(IdentificationTypeRepository $identificationTypeRepository)
    {
        $this->identificationTypeRepository = $identificationTypeRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $identificationTypes =  $this->identificationTypeRepository->search([])->get();

        return view('identification_types.index', compact('identificationTypes'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('identification_types.create');
    }

    public function edit($id)
    {
        $identificationType =  $this->identificationTypeRepository->getById($id);
        return view('identification_types.edit',compact('identificationType'));
    }



}
