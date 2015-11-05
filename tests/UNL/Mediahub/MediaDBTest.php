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
     * Test the canView Logic
     * 
     * @test
     */
    public function canView()
    {
        $this->prepareTestDB();

        //Private video
        $media_d = UNL_MediaHub_Media::getById(4);
        
        //unlisted video
        $media_e = UNL_MediaHub_Media::getById(3);
        
        //public video
        $media_c = UNL_MediaHub_Media::getById(5);

        $user_a = UNL_MediaHub_User::getByUid('test_a');
        $user_b = UNL_MediaHub_User::getByUid('test_b');

        //Test a video that is private
        $this->assertTrue($media_d->canView($user_a));
        $this->assertFalse($media_d->canView($user_b));

        //Test a video that is unlisted
        $this->assertTrue($media_e->canView($user_a));
        $this->assertTrue($media_e->canView($user_b));

        //Test a video that is public
        $this->assertTrue($media_c->canView($user_a));
        $this->assertTrue($media_c->canView($user_b));
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