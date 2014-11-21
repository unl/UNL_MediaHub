<?php

class UNL_MediaHub_MediaDBTest extends UNL_MediaHub_DBTests_DBTestCase
{
    /**
     * Test the logic that determines if a user can edit media is working
     * @test
     */
    public function userCanEdit()
    {
        $this->prepareTestDB();
        
        $media_a = UNL_MediaHub_Media::getById(1);
        
        $user_a = UNL_MediaHub_User::getByUid('test_a');
        $user_b = UNL_MediaHub_User::getByUid('test_b');
        
        $this->assertTrue($media_a->userCanEdit($user_a));
        $this->assertFalse($media_a->userCanEdit($user_b));
    }
}