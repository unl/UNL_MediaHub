<?php

class UNL_MediaHub_Feed_Media_FeedSelectionDBTest extends UNL_MediaHub_DBTests_DBTestCase
{
    /**
     * @test
     */
    public function getFeedSelectionData()
    {
        $this->prepareTestDB();

        $media_a = UNL_MediaHub_Media::getById(3);
        $user_a = UNL_MediaHub_User::getByUid('test_a');
        
        $feed_selection = new UNL_MediaHub_Feed_Media_FeedSelection($user_a, $media_a);
        $data = $feed_selection->getFeedSelectionData();
        
        //feed_a is selected and can be changed
        $this->assertEquals(false, $data[1]['readonly'], 'feed_a should be editable by user test_a');
        $this->assertEquals(true, $data[1]['selected'], 'feed_a should be selected');
        
        //Feed be is selected and can not be changed by user test_a
        $this->assertEquals(true, $data[2]['readonly'], 'feed_b should be be editable by user test_a because they do not have permission');
        $this->assertEquals(true, $data[2]['selected'], 'feed_b should be selected');
    }
}