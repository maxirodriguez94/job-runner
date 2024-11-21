<?php

namespace App\Http\Controllers;
use App\Http\Middleware\RoleMiddleware;

use App\Models\Job;


class JobController extends Controller {

  

    public function index(){

        $jobs = Job::orderBy('priority', 'desc')  
                    ->orderBy('created_at', 'desc')  
                    ->paginate(10);
                    
        return view('jobs.job', compact('jobs'));
    }

    public function cancel( $id ) {
        $job = Job::findOrFail( $id );

        if ( $job->status === 'running' ) {
            $job->update( [ 'status' => 'cancelled' ] );
        }

        return redirect()->route( 'jobs.index' )->with( 'status', 'Trabajo cancelado correctamente.' );
    }

    public function delete( $id ) {
        $job = Job::findOrFail( $id );

        $job->delete();

        return redirect()->route( 'jobs.index' )->with( 'status', 'Trabajo eliminado correctamente.' );
    }
}

