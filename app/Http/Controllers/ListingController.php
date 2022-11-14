<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    // show all job listing
    public function index(){
        return view('listings.index', [
            'listings' => Listing::latest()
                ->filter(request(['tag', 'search']))
                ->paginate(6)
        ]);
    }

    // show single job listing
    // we have used route model binding in laravel
    public function show(Listing $listing){
        return view('listings.show', [
            'listing' => $listing
        ]);
    }

    // show create form
    public function create(){
        return view('listings.create');
    }

    // store new listing
    public function store(Request $request){
        $formFields = $request->validate([
            'title' => 'required',
            'company' => 'required',
            'location' => 'required',
            'email' => ['required', 'email'],
            'website' => 'required',
            'tags' => 'required',
            'description' => 'required'
        ]);

        if ($request->hasFile('logo')){

            // add file name in formFields and store it in the logos folder inside public
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $formFields['user_id'] = auth()->id();

        // create listing in database
        Listing::create($formFields);

        return redirect('/')->with('success', 'Listing Created Successfully');
    }

    public function edit(Listing $listing){
        $userID = auth()->id();
        if($listing->user_id !== $userID){
            return redirect('/listings/'.$listing->id)->with('error', 'only owner can update listing');
        }

        // dd($listing);
        return view('listings.edit', ['listing' => $listing]);
    }

    public function update(Listing $listing, Request $request){

        $userID = auth()->id();
        if($listing->user_id !== $userID){
            return redirect('/')->with('error', 'only owner can update listing');
        }

        $formFields = $request->validate([
            'title' => 'required',
            'company' => 'required',
            'location' => 'required',
            'email' => ['required', 'email'],
            'website' => 'required',
            'tags' => 'required',
            'description' => 'required'
        ]);

        if ($request->hasFile('logo')){

            // add file name in formFields and store it in the logos folder inside public
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        // create listing in database
        $listing->update($formFields);

        return redirect('/listings/'.$listing->id)->with('success', 'Listing Updated Successfully');
    }

    public function destroy(Listing $listing){
        $userID = auth()->id();

        if($listing->user_id !== $userID){
            return redirect('/listings/'.$listing->id)->with('error', 'only owner can delete listing');
        }
        $listing->delete();
        return redirect('/')->with('success', 'Listing delete successfully');
    }

    public function manageListing(){
        $listings = auth()->user()->listings()->get();

        return view('listings.manage', [
            'listings' => $listings
        ]);
    }
}
