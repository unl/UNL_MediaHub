<?php
/*
 *  $Id: XcacheTestCase.php 7490 2010-03-29 19:53:27Z jwage $
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.doctrine-project.org>.
 */

/**
 * Doctrine_Cache_Xcache_TestCase
 *
 * @package     Doctrine
 * @subpackage  Doctrine_Cache
 * @author      David Abdemoulaie <dave@hobodave.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.2
 * @version     $Revision: 7490 $
 */
class Doctrine_Cache_Xcache_TestCase extends Doctrine_Cache_Abstract_TestCase
{
    protected function _clearCache()
    {
        for ($i = 0, $count = xcache_count(XC_TYPE_VAR); $i < $count; $i++) {
            xcache_clear_cache(XC_TYPE_VAR, $i);
        }
    }

    protected function _isEnabled()
    {
        // Wow, Xcache doesn't work in CLI by design
        if (array_key_exists('SHELL', $_ENV)) {
            return false;
        }

        return extension_loaded('xcache');
    }

    protected function _getCacheDriver()
    {
        return new Doctrine_Cache_Xcache();
    }
}
