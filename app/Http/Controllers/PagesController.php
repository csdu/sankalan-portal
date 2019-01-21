<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index() {
        $questions = collect(range(1, 50))->map(function ($item, $index) {
            return [
                'id' => $index + 1,
                'question' => 'What is lorem ipsum text-generator?\nLorem ipsum, dolor sit amet consectetur adipisicing elit. Accusamus asperiores laboriosam harum quod atque veniam a culpa magnam enim voluptatibus impedit illo iure exercitationem cumque, tenetur optio et iste assumenda!',
                'options' => collect([
                    ['value' => 1, 'text' => 'Option 1'],
                    ['value' => 2, 'text' => 'Option 2'],
                    ['value' => 3, 'text' => 'Option 3'],
                    ['value' => 4, 'text' => 'Option 4'],
                ])->shuffle()
            ];
        });
        return view('welcome', compact('questions'));
    }
}
