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

    /**
     * Test the delete logic is working and deleted all related entries
     * @test
     */
    public function delete()
    {
        $this->prepareTestDB();

        $media_a = UNL_MediaHub_Media::getById(1);
        $media_a->delete();
        
        //Verify feeds were removed
        $feed_list = $media_a->getFeeds();
        $feed_list->run();
        $this->assertEquals(0, count($feed_list->items));

        //Verify ns elements have been removed
        $db = Doctrine_Manager::getInstance()->getCurrentConnection();
        $q = $db->prepare(
            "SELECT * 
            FROM media_has_nselement
            WHERE media_id = ?"
        );

        $q->execute(array($media_a->id));
        $ns_elements = $q->fetchAll();

        $this->assertEquals(0, count($ns_elements));
    }
}