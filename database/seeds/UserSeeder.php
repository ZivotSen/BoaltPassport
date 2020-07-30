<?php
/**
 * Created by PhpStorm.
 * User: ageorge
 * Date: 7/28/2020
 * Time: 11:45 PM
 */

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\PHP;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        $this->generateHundredDummyUsers();
    }

    // Function to generate a first 100 users
    private function generateHundredDummyUsers(){
        $commonPassword = "HundredUsers"; // Common password for all first 100 users
        $count = 0;
        while ($count < 100){
            $name = $this->randomName();
            if(!empty($name)){
                $email = $this->getEmail($name);

                // Search if email is already present in the user DB
                $exist = DB::table('users')->select('id')->where('email', $email)->first();
                if(is_null($exist) || empty($exist) || !$exist){
                    DB::table('users')->insert(array(
                        'name' => $name,
                        'email' => $email,
                        'password' => Hash::make($commonPassword),
                        'created_at' => new \DateTime('now'),
                        'updated_at' => new \DateTime('now')
                    ));
                    $count++;
                }
            }
        }
    }

    /**
     * This function use an external API connection with (https://api.namefake.com/) to get a random name
     */
    private function randomName() {
        $name = "";
        $gender = [
            'male', 'female'
        ];
        $randomGender = array_rand($gender, 1);
        $url = "https://api.namefake.com/english-united-states/{$gender[$randomGender]}/";
        $call = call_user_func(array(PHP\Utils::class, 'getRequest'), $url, "POST");

        if(call_user_func(array(PHP\Utils::class, 'isJSON'), $call)){
            $result = call_user_func(array(PHP\Utils::class, 'jsonToArray'), $call);
            $name = $result['name'];
        }

        if(!empty($name)){
            $portions = explode(" ", $name);
            $titles = ["Ms.", "Mr.", "Dr.", "Jr.", "Prof."];
            if(in_array(trim($portions[0]), $titles)){
                $name = trim($portions[1]);
            } else {
                $name = trim($portions[0]);
            }
        }

        return $name;
    }

    private function getEmail($name){
        return strtolower($name)."@seeder.com";
    }
}
