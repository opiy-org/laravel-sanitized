<?php

declare(strict_types=1);

namespace OpiyOrg\Laravelsanitized\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Schema;
use OpiyOrg\LaravelSanitized\LaravelSanitizedServiceProvider;
use OpiyOrg\Laravelsanitized\Tests\Models\DirtyArticle;
use OpiyOrg\Laravelsanitized\Tests\Models\DirtyPost;
use Orchestra\Testbench\TestCase;

/**
 * SanitizedTest
 */
class SanitizedTest extends TestCase
{
    /**
     * @param Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.env', 'testing');
        $app['config']->set('app.debug', 'true');
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('hashing', ['driver' => 'bcrypt']);

        Schema::create('dirty_articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->text('body')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('dirty_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->text('body')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * @param Application $app
     * @return string[]
     */
    protected function getPackageProviders($app)
    {
        return [
            LaravelSanitizedServiceProvider::class,
        ];
    }

    /**
     * @group smoke
     */
    public function testCreatingModelCleanSuccess(): void
    {
        $item = DirtyPost::create([
            'title' => 'test',
            'body' => '<script>alert(1);</script><b style="text-indent: 15pt;">clean text</b>',
        ]);

        $this->assertEquals('<b>clean text</b>', $item->body);
    }

    /**
     * @group smoke
     */
    public function testUpdatingModelCleanSuccess(): void
    {
        $item = DirtyPost::create([
            'title' => 'test',
            'body' => 'fake',
        ]);
        $item->update([
            'body' => '<iframe src="http://google.com"/><i class="someClass">test</i><em class="secondClass">em</em>',
        ]);

        $this->assertEquals('<i class="someClass">test</i><em>em</em>', $item->body);
    }

    /**
     * @group smoke
     */
    public function testCreatingModelNotCleanNotInList(): void
    {
        $html = '<script>alert(1235); let i = 12; </script>test';
        $item = DirtyPost::create([
            'title' => 'test',
            'body' => $html,
            'description' => $html,
        ]);

        $this->assertEquals('test', $item->body);
        $this->assertEquals($html, $item->description);
    }

    /**
     * @group smoke
     */
    public function testCreatingModelCleanFailed(): void
    {
        $html = '<script>alert(1);</script><b>clean</b>';
        $item = DirtyArticle::create([
            'title' => 'test',
            'body' => $html,
        ]);

        $this->assertEquals($html, $item->body);
    }
}
