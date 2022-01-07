
<?php

namespace App\Repositories\Frontend\Access\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Auth;
use Exception;
use Intervention\Image\Facades\Image;


/**
 * Class UserRepository.
 */
class UserRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = User::class;

    /**
     * @var RoleRepository
     */
    protected $role;
    protected $dog_id;

    /**
     * @param RoleRepository $role
     */
    public function __construct(RoleRepository $role)
    {
        $this->role = $role;
    }

   public function create(array $data, $provider = false)
    {
        $image1 = $data['dog_image'];
        $dogimage = time() . '.' . $image1->getClientOriginalName();
        $destinationPath2 = public_path('/images/thumbnails/dog');
        $img1 = Image::make($image1->getRealPath());
        $img1->resize(100, 100, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath2 . '/' . $dogimage);
        $destinationPath3 = public_path('/images/dog');
        $image1->move($destinationPath3, $dogimage);
  }
}
