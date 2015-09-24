<?php

class UNL_MediaHub_MediaListDBTest extends UNL_MediaHub_DBTests_DBTestCase
{
    /**
     * Test the delete logic is working and deleted all related entries
     * @test
     */
    public function privacyTest()
    {
        $this->prepareTestDB();
        
        $user_a = UNL_MediaHub_User::getByUid('test_a');
        $user_b = UNL_MediaHub_User::getByUid('test_b');
        
        //Logged out - most recent
        $list = new UNL_MediaHub_MediaList();
        $list->run();
        
        foreach ($list->items as $media) {
            $this->assertEquals('PUBLIC', $media->privacy, 'recent media for logged out users should be public');
        }

        //Logged in (user A) - most recent
        UNL_MediaHub_AuthService::getInstance()->setUser($user_a);
        
        $list = new UNL_MediaHub_MediaList();
        $list->run();
        $expected_ids = array(
            1, //media_a because it is new and missing a text track, but user is part of its feed
            2, //media_b because it is public (new and has text track)
            3, //media_c because it is public (and old, missing text track)
            4, //media_d because it is public (and old, missing text track)
            5, //media_e because it is public (and old, missing text track)
            //NOT media_f because it is private
            //NOT media_g because it is unlisted
        );
        $found_ids = $this->getListIds($list);
        sort($found_ids);
        $this->assertEquals($expected_ids, $found_ids, 'users with access to media should see private and unlisted media');

        //Logged in (user B) - most recent
        UNL_MediaHub_AuthService::getInstance()->setUser($user_b);

        $list = new UNL_MediaHub_MediaList();
        $list->run();
        $expected_ids = array(
            //NOT media_a because it is new and missing a text track
            2, //media_b because it is public (new and has text track)
            3, //media_c because it is public (and old, missing text track)
            //4, //NOT media_d because it is private (and user is not a member of its feed)
            //5, //NOT media_e because it is unlisted (and user is not a member of its feed)
            6, //media_f because it is private (because user_b in feed_b, which media_f is in)
            7, //media_g because it is unlisted (because user_b in feed_b, which media_g is in)
        );
        $found_ids = $this->getListIds($list);
        sort($found_ids);
        $this->assertEquals($expected_ids, $found_ids, 'users with access to media should see private and unlisted media');
    }
    
    protected function getListIds(UNL_MediaHub_MediaList $list)
    {
        $ids = array();
        foreach ($list->items as $media) {
            $ids[] = $media->id;
        }
        
        return $ids;
    }
}