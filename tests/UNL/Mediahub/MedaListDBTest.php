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
        $expected_ids = array(1,2,3,4,5);
        $found_ids = $this->getListIds($list);
        sort($found_ids);
        $this->assertEquals($expected_ids, $found_ids, 'users with access to media should see private and unlisted media');

        //Logged in (user B) - most recent
        UNL_MediaHub_AuthService::getInstance()->setUser($user_b);

        $list = new UNL_MediaHub_MediaList();
        $list->run();
        $expected_ids = array(1,2,3,6,7);
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