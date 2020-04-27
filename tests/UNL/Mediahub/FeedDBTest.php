<?php

class UNL_MediaHub_FeedDBTest extends UNL_MediaHub_DBTests_DBTestCase
{
    /**
     * Test the delete logic is working and deleted all related entries
     * @test
     */
    public function delete()
    {
        $this->prepareTestDB();

        $feed_a     = UNL_MediaHub_Feed::getById(1);
        $media_list = $feed_a->getMediaList();
        $user_list  = $feed_a->getUserList();
        
        $feed_a->delete();

        //Verify media was removed
        $media_list->ran = false; //force a re-run
        $media_list->run();
        $this->assertEquals(0, count($media_list->items), 'feed media should be removed');
        
        //Verify users were removed
        $user_list->ran = false; //force a re-run
        $user_list->run();
        $this->assertEquals(0, count($user_list->items), 'feed users should be removed');

        //Verify ns elements have been removed
        $db = Doctrine_Manager::getInstance()->getCurrentConnection();
        $q = $db->prepare(
            "SELECT * 
            FROM feed_has_nselement
            WHERE feed_id = ?"
        );

        $q->execute(array($feed_a->id));
        $ns_elements = $q->fetchAll();

        $this->assertEquals(0, count($ns_elements), 'feed ns elements should be removed');
    }
}