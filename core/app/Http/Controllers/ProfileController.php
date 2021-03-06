<?php namespace App\Http\Controllers;

use App\Http\Requests\ProfileFormRequest;
use App\Invoicer\Repositories\Contracts\ProfileInterface as Profile;

class ProfileController extends Controller {
    private $profile;

    public function __construct(Profile $profile){
        $this->middleware('auth');
        $this->profile = $profile;
    }

    /**
     * Show the form for editing the specified resource.
     * @return \Illuminate\View\View
     */
    public function edit()
	{
        if (\Auth::user())
        {
            $user = $this->profile->getById(\Auth::user()->id);
            return view('users.profile', compact('user'));
        }
        return redirect('profile');
	}

    /**
     * Update the specified resource in storage.
     * @param ProfileFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(ProfileFormRequest $request)
	{
        if (\Auth::user())
        {
            $user = $this->profile->getById(\Auth::user()->id);
            $data =  array(
                      'username'=>$request->username,
                      'name'=>$request->name,
                      'email'=>$request->email,
                      'phone'=> $request->phone,
            );
            if ($request->hasFile('photo'))
            {
                $file = $request->file('photo');
                $filename = strtolower(str_random(50) . '.' . $file->getClientOriginalExtension());
                $file->move('assets/img/uploads', $filename);
                \Image::make(sprintf('assets/img/uploads/%s', $filename))->resize(200, 200)->save();
                \File::delete('assets/img/uploads/'.$user->photo);
                $data['photo']= $filename;
            }

            if($request->get('password') != ''){
                $data['password']= bcrypt($request->password);
            }
            $this->profile->updateById($user->id, $data);

            flash()->success('Profile updated');
            return redirect('profile');
        }
	}



}
