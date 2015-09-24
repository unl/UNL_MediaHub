<?php
class UNL_MediaHub_DBTests_BaseTestDataInstaller implements UNL_MediaHub_DBTests_MockTestDataInstallerInterface
{
    /**
     * This function should execute commands to install mock data to the test database.
     */
    public function install() {
        //Create users
        $user_a = new UNL_MediaHub_User();
        $user_a->uid = 'test_a';
        $user_a->save();

        $user_b = new UNL_MediaHub_User();
        $user_b->uid = 'test_b';
        $user_b->save();
        
        //Create some channels
        $feed_a = new UNL_MediaHub_Feed();
        $feed_a->title = 'test a';
        $feed_a->uidcreated = $user_a->uid;
        $feed_a->save();

        $element = new UNL_MediaHub_Feed_NamespacedElements_media();
        $element->feed_id = $feed_a->id;
        $element->element = 'title';
        $element->value   = $feed_a->title;
        $element->save();

        $feed_b = new UNL_MediaHub_Feed();
        $feed_b->title = 'test b';
        $feed_b->uidcreated = $user_b->uid;
        $feed_b->save();

        $element = new UNL_MediaHub_Feed_NamespacedElements_media();
        $element->feed_id = $feed_b->id;
        $element->element = 'title';
        $element->value   = $feed_b->title;
        $element->save();
        
        //Add some permissions
        $feed_a->addUser($user_a);
        $feed_b->addUser($user_b);
        
        //add some media
        $media_a = new UNL_MediaHub_Media();
        $media_a->url         = 'http://example.org/a.mov';
        $media_a->uidcreated  = $user_a->uid;
        $media_a->uidupdated  = $user_a->uid;
        $media_a->type        = 'video/mp4';
        $media_a->title       = 'Test Media A';
        $media_a->description = 'Test Media A Description';
        $media_a->datecreated = '2015-09-01 00:00:00';
        $media_a->save();

        $media_b = new UNL_MediaHub_Media();
        $media_b->url         = 'http://example.org/B.mov';
        $media_b->uidcreated  = $user_a->uid;
        $media_b->uidupdated  = $user_a->uid;
        $media_b->type        = 'audio/mp3';
        $media_b->title       = 'Test Media B';
        $media_b->description = 'Test Media B Description';
        $media_b->datecreated = '2015-09-01 00:00:00';
        $media_b->save();

        $media_c = new UNL_MediaHub_Media();
        $media_c->url         = 'http://example.org/C.mov';
        $media_c->uidcreated  = $user_a->uid;
        $media_c->uidupdated  = $user_a->uid;
        $media_c->type        = 'audio/mp3';
        $media_c->title       = 'Test Media C';
        $media_c->description = 'Test Media C Description';
        $media_c->datecreated = '2015-09-01 00:00:00';
        $media_c->save();

        $media_d = new UNL_MediaHub_Media();
        $media_d->url         = 'http://example.org/private.mov';
        $media_d->uidcreated  = $user_a->uid;
        $media_d->uidupdated  = $user_a->uid;
        $media_d->type        = 'audio/mp3';
        $media_d->title       = 'Test Media - Private in feed A';
        $media_d->description = 'for testing';
        $media_d->privacy     = 'PRIVATE';
        $media_d->datecreated = '2015-09-01 00:00:00';
        $media_d->save();

        $media_e = new UNL_MediaHub_Media();
        $media_e->url         = 'http://example.org/unlisted.mov';
        $media_e->uidcreated  = $user_a->uid;
        $media_e->uidupdated  = $user_a->uid;
        $media_e->type        = 'audio/mp3';
        $media_e->title       = 'Test Media - Unlisted in feed A';
        $media_e->description = 'for testing';
        $media_e->privacy     = 'UNLISTED';
        $media_e->datecreated = '2015-09-01 00:00:00';
        $media_e->save();

        $media_f = new UNL_MediaHub_Media();
        $media_f->url         = 'http://example.org/private.mov';
        $media_f->uidcreated  = $user_b->uid;
        $media_f->uidupdated  = $user_b->uid;
        $media_f->type        = 'audio/mp3';
        $media_f->title       = 'Test Media - Private in feed B';
        $media_f->description = 'for testing';
        $media_f->privacy     = 'PRIVATE';
        $media_f->datecreated = '2015-09-01 00:00:00';
        $media_f->save();

        $media_g = new UNL_MediaHub_Media();
        $media_g->url         = 'http://example.org/unlisted.mov';
        $media_g->uidcreated  = $user_b->uid;
        $media_g->uidupdated  = $user_b->uid;
        $media_g->type        = 'audio/mp3';
        $media_g->title       = 'Test Media - Unlisted in feed B';
        $media_g->description = 'for testing';
        $media_g->privacy     = 'UNLISTED';
        $media_g->datecreated = '2015-09-01 00:00:00';
        $media_g->save();
        
        //Add media to channels
        $feed_a->addMedia($media_a);
        $feed_b->addMedia($media_b);
        $feed_a->addMedia($media_c);
        $feed_b->addMedia($media_c);
        $feed_a->addMedia($media_d);
        $feed_a->addMedia($media_e);
        $feed_b->addMedia($media_f);
        $feed_b->addMedia($media_g);
    }
}