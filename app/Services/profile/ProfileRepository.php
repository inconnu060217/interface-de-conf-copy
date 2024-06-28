<?php

namespace App\Services\profile;
use App\Models\ProfileModel;
class ProfileRepository implements IProfileRepository
{
    public function getAllProfile(){
        return ProfileModel::select("kname")->get()->toArray();
    }
}
