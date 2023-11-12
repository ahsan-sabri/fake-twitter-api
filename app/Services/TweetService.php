<?php

namespace App\Services;

use App\Models\Follow;
use App\Models\Tweet;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class TweetService
{
    public function getUserTweets($userId) {
        return User::find($userId)->tweets;
    }

    public function getUserTimeline($userId)
    {
        $user = User::find($userId);
        $timelineTweeters = [$userId];
        $followingIds = $user->following()->pluck('id')->all();
        $timelineTweeters = array_merge($timelineTweeters, $followingIds);

        return Tweet::whereIn('user_id', $timelineTweeters)
                    ->orderBy('created_at', 'desc')
                    ->with(['tweeter:id,name,username'])
                    ->paginate(20);
    }

    public function storeTweet($data) {
        return Tweet::create($data);
    }

    public function getTweetDetails($tweet) {
        return $tweet->load('tweeter:id,name,username');
    }
}
