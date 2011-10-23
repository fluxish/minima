<?php

namespace Kaili;

/**
 * Test class for Directory.
 * Generated by PHPUnit on 2011-10-22 at 10:55:52.
 */
class DirectoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Directory
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {    
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $test_dirs = array(
            ROOT.DS.'test_dir',
            ROOT.DS.'test_new_dir',
            SYSTEM.DS.'test_dir',
        );
        foreach($test_dirs as $f){
            is_dir($f) and rmdir($f);
        }
    }
    
    /**
     * Test for Directory::factory()
     * @test
     */
    public function test_factory()
    {
        $object = Directory::factory(ROOT.DS.'application');
        $this->assertEquals($object->get_path(), ROOT.DS.'application');
    }
    
    /**
     * Test for Directory::factory()
     * The directory doesn't exist.
     * Throws Kaili\DirectoryException because the provided directory doesn't exist.
     * @test
     * @expectedException \Kaili\DirectoryException
     */
    public function test_factory_not_exist()
    {
        Directory::factory(ROOT.DS.'test_dir');
    }
    
    /**
     * Test for Directory::create()
     * @test
     */
    public function test_create()
    {
        $object = Directory::create(ROOT.DS.'test_dir');
        $this->assertEquals($object->get_path(), ROOT.DS.'test_dir');
    }
    
    /**
     * Test for Directory::create()
     * The Directory already exist.
     * Throws Exception because provided path is an already existent Directory
     * @test
     * @expectedException \Kaili\DirectoryException
     */
    public function test_create_exists()
    {
        Directory::create(ROOT.DS.'application');
    }
    
    /**
     * Test for Directory::rename()
     * Create a new directory named test_dir and renames it as test_new_dir.
     * At the end of the test, remove the created directory.
     * @test
     */
    public function test_rename()
    {
        $object = Directory::create(ROOT.DS.'test_dir');
        $object->rename('test_new_dir');
        $this->assertEquals($object->get_base_name(), 'test_new_dir');
    }
    
    /**
     * Test for Directory::rename()
     * Create a new Directory named test_dir and renames it with the same name.
     * At the end of the test, remove the created directory.
     * @test
     */
    public function test_rename_same_name()
    {
        $object = Directory::create(ROOT.DS.'test_dir');
        $object->rename('test_dir');
        $this->assertEquals($object->get_base_name(), 'test_dir');
    }
    
    /**
     * Test for Directory::move()
     * Create the Directory test_dir in [ROOT] and moves it to [SYSTEM]
     * At the end of the test, remove the moved directory.
     * @test
     */
    public function test_move()
    {
        $object = Directory::create(ROOT.DS.'test_dir');
        $object->move(SYSTEM);
        $this->assertTrue(is_dir(SYSTEM.DS.'test_dir'));
        $this->assertFalse(is_dir(ROOT.DS.'test_dir'));
        $this->assertEquals($object->get_path(), SYSTEM.DS.'test_dir');
    }
    
     /**
     * Test for Directory::move()
     * Attempts to move [ROOT]/test.txt to [ROOT] with overwriting disabled
     * Throws \Kaili\DirectoryException because Directory alredy exists
     * @expectedException \Kaili\DirectoryException
     * @test
     */
    public function test_move_directory_exists()
    {
        $object = Directory::create(ROOT.DS.'test_dir');
        $object->move(ROOT, false);
    }
    
    /**
     * Test for Directory::move()
     * Attempts to move [ROOT]/index.php to [ROOT]/not_exist
     * Throws \InvalidArgumentException because provided path doesn't exist
     * @expectedException \InvalidArgumentException
     * @test
     */
    public function test_move_not_exists_dir()
    {
        $object = Directory::create(ROOT.DS.'test_dir');
        $object->move(ROOT.DS.'not_exist');
    }
    
    
    /**
     * Test for Directory::remove()
     * Create and remove [ROOT]/test_dir
     * @test
     */
    public function test_remove()
    {
        $path = ROOT.DS.'test_dir';
        $object = Directory::create($path);
        $object->remove($path);
        $this->assertFalse(is_dir($path));
    }
    
    /**
     * Test for Directory::scan()
     * Scan the content of ROOT directory
     * @test
     */
    public function test_scan()
    {
        $content = array('.','..','application','.git','.gitignore','.htaccess','index.php','nbproject','README','system');
        $object = Directory::factory(ROOT);
        $res = $object->scan(Directory::SORT_ASC);
        
        $output = array();
        foreach($res as $f) $output[] = $f->get_base_name();
        $this->assertEquals($content, $output);
    }
    
    /**
     * Test for Directory::scan()
     * Scan the content of ROOT directory in descending order
     * @test
     */
    public function test_scan_sort_desc()
    {
        $content = array('.','..','application','.git','.gitignore','.htaccess','index.php','nbproject','README','system');
        $object = Directory::factory(ROOT);
        $res = $object->scan(Directory::SORT_DESC);
        
        $output = array();
        foreach($res as $f) $output[] = $f->get_base_name();
        $this->assertEquals(array_reverse($content), $output);
    }
    
    /**
     * Test for Directory::scan()
     * Scan only the directories inside ROOT directory
     * @test
     */
    public function test_scan_directories()
    {
        $content = array('.','..','application','.git','nbproject','system');
        $object = Directory::factory(ROOT);
        $res = $object->scan(Directory::SORT_ASC, Directory::SCAN_DIRS);
        
        $this->assertEquals(count($content), count($res));
        foreach($res as $path=>$dir){
            $this->assertTrue(in_array($dir->get_base_name(), $content));
            $this->assertInstanceOf('\Kaili\Directory', $dir);
        }
    }
    
    /**
     * Test for Directory::scan()
     * Scan only the files inside ROOT directory
     * @test
     */
    public function test_scan_files()
    {
        $content = array('.gitignore','.htaccess','index.php','README');
        $object = Directory::factory(ROOT);
        $res = $object->scan(Directory::SORT_ASC, Directory::SCAN_FILES);
        
        $this->assertEquals(count($content), count($res));
        foreach($res as $path=>$dir){
            $this->assertTrue(in_array($dir->get_base_name(), $content));
            $this->assertInstanceOf('\Kaili\File', $dir);
        }
    }
    
    /**
     * Test for Directory::search()
     * Search file 'index.php' inside the ROOT directory
     * @test
     */
    public function test_search()
    {
        $object = Directory::factory(ROOT);
        $res = $object->search('/(index.php|.htaccess)/');
        
        $this->assertArrayHasKey(ROOT.DS.'index.php', $res);
        $this->assertArrayHasKey(ROOT.DS.'.htaccess', $res);
    }
    
    /**
     * Test for Directory::search_by_name()
     * Search file 'index.php' inside the ROOT directory
     * @test
     */
    public function test_search_by_name()
    {
        $object = Directory::factory(ROOT);
        $res = $object->search_by_name('index.php');
        
        $this->assertArrayHasKey(ROOT.DS.'index.php', $res);
    }
    
    /**
     * Test for Directory::search_by_extension()
     * Search files with 'php' extension inside the ROOT directory
     * @test
     */
    public function test_search_by_extension()
    {
        $object = Directory::factory(ROOT);
        $res = $object->search_by_extension(array('php'));
        
        $this->assertArrayHasKey(ROOT.DS.'index.php', $res);
    }
}

?>
