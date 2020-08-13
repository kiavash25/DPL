<?php

namespace App\Http\Controllers;

use App\Events\makeLog;
use App\models\Booking;
use App\models\Countries;
use App\models\Destination;
use App\models\Log;
use App\models\Package;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function packageBook($packageId)
    {
        $country = Countries::select(['id', 'countryName'])->orderBy('countryName')->get();

        $package = Package::find($packageId);
        if($package == null)
            return redirect(url('/'));

        $package->destination = Destination::find($package->destId)->name;

        if($package->brochure != null)
            $package->brochure = asset('uploaded/packages/' . $package->id . '/' . $package->brochure);

        if($package->capacity != null){
            $registered = Booking::where('eventKind', 'package')->where('eventId', $package->id)->count();
            $package->availableCap = $package->capacity - $registered;
            if($package->availableCap < 1)
                return redirect(route('show.package', ['slug' => $package->slug]));

            $package->capacity = $package->availableCap;
        }
        else
            $package->capacity = -1;

        return view('main.booking.packageBooking', compact(['package', 'country']));
    }

    public function packageBookStore(Request $request)
    {
        if( isset($request->firstName) && isset($request->lastName) &&
            isset($request->natId) && isset($request->country) &&
            isset($request->gender) && isset($request->day) &&
            isset($request->month) && isset($request->year) && isset($request->leaderId)){

            $newBooking = new Booking();
            $newBooking->eventKind = 'package';
            $newBooking->eventId = $request->packageId;
            $newBooking->userId = auth()->user()->id;

            if($request->leaderId == 0){
                if(!(isset($request->address) && isset($request->postalCode) &&
                    isset($request->email) && isset($request->phone))){
                    echo json_encode(['status' => 'nok1']);
                    return;
                }
                $code = generateRandomString(10);
                while(Booking::where('code', $code)->count() != 0)
                    $code = generateRandomString(10);

                $newBooking->code = generateRandomString(10);
                $newBooking->address = $request->address;
                $newBooking->addressCode = $request->postalCode;
                $newBooking->email = $request->email;
                $newBooking->phone = $request->phone;
                $newBooking->relatedId = 0;
            }
            else{
                $check = Booking::find($request->leaderId);
                if($check == null){
                    echo json_encode(['status' => 'notLeaderFound']);
                    return;
                }
                $newBooking->relatedId = $request->leaderId;
                $newBooking->code = $check->code;
            }

            if($request->sDate != 0)
                $newBooking->sDate = $request->sDate;
            if($request->eDate != 0)
                $newBooking->eDate = $request->eDate;

            $newBooking->firstName = $request->firstName;
            $newBooking->lastName = $request->lastName;
            $newBooking->nationalId = $request->natId;
            $newBooking->countryId = $request->country;
            $newBooking->gender = $request->gender == 'male' ? 1 : 0;
            $newBooking->birthDate = $request->year . '-' . $request->month . '-' . $request->day;

            $location = __DIR__ . '/../../../public/uploaded/users';
            if(!is_dir($location))
                mkdir($location);

            $location .= '/' . auth()->user()->id;
            if(!is_dir($location))
                mkdir($location);

            $image = $request->file('natPic');
            $dirs = 'uploaded/users/' . auth()->user()->id;
            $size = [
                [
                    'width' => 600,
                    'height' => null,
                    'name' => '',
                    'destination' => $dirs
                ],
            ];
            $fileName = resizeImage($image, $size);
            if($fileName == 'error'){
                echo json_encode(['status' => 'cantUploadPic']);
                return;
            }
            $newBooking->nationalPic = $fileName;
            $newBooking->save();

            event(new makeLog([
                'subject' => 'package_booking',
                'referenceId' => $newBooking->id,
                'referenceTable' => 'bookings'
            ]));

            $leaderId = $request->leaderId;
            if($leaderId == 0)
                $leaderId = $newBooking->id;

            echo json_encode(['status' => 'ok', 'leaderId' => $leaderId]);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function deleteAllBooking(Request $request)
    {
        $leaderId = $request->leaderId;
        if($leaderId != 0){
            $booking = Booking::where('relatedId', $leaderId)->get();
            foreach ($booking as $book){
                Log::where('subject', 'package_booking')->where('referenceId', $book->id)->delete();
                $location = __DIR__ .'/../../../public/uploaded/users/'.$book->userId.'/'.$book->nationalPic;
                if(is_file($location))
                    unlink($location);

                $book->delete();
            }
            $book = Booking::find($leaderId);
            Log::where('subject', 'package_booking')->where('referenceId', $book->id)->delete();
            $location = __DIR__ .'/../../../public/uploaded/users/'.$book->userId.'/'.$book->nationalPic;
            if(is_file($location))
                unlink($location);

            $book->delete();
        }

        echo 'ok';
        return;
    }
}
