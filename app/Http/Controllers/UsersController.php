<?php

namespace App\Http\Controllers;

use App\Repositories\IdentificationTypeRepository;
use App\Repositories\UserRepository;
use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /** @var IdentificationTypeRepository  */
    protected $identificationTypeRepository;

    /**
     * UsersController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository, IdentificationTypeRepository $identificationTypeRepository)
    {
        $this->userRepository = $userRepository;
        $this->identificationTypeRepository = $identificationTypeRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users =  $this->userRepository->selectAll();
        return view('users.index', compact('users'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $identificationTypes = $this->identificationTypeRepository->search([])->get();
        return view('users.create', compact('identificationTypes'));
    }

    public function edit($id)
    {
        $user =  $this->userRepository->selectById($id);
        $identificationTypes = $this->identificationTypeRepository->search([])->get();
        return view('users.edit',compact('user', 'identificationTypes'));
    }

}
