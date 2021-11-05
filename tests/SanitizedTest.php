<?php

declare(strict_types=1);

namespace OpiyOrg\Laravelsanitized\Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Schema;
use OpiyOrg\LaravelSanitized\LaravelSanitizedServiceProvider;
use OpiyOrg\LaravelSanitized\Sanitized;
use Orchestra\Testbench\TestCase;

/**
 * SanitizedTest
 */
class SanitizedTest extends TestCase
{
    /**
     * @param Application $app
     */
    protected function getEnvironmentSetUp(Application $app): void
    {
        $app['config']->set('app.debug', 'true');
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('hashing', ['driver' => 'bcrypt']);

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
    protected function getPackageProviders(Application $app): array
    {
        return [
            LaravelSanitizedServiceProvider::class,
        ];
    }

    /**
     * @group smoke
     */
    public function testCreatingModelCleanSuccess()
    {
        $item = DirtyPost::create([
            'title' => 'test',
            'body' => '<script>alert(1);</script><b>clean</b>',
        ]);

        $item->refresh();

        $this->assertEquals('<b>clean</b>', $item->body);
    }

    /**
     * @group smoke
     */
    public function testUpdatingModelCleanSuccess()
    {
        $item = DirtyPost::create([
            'title' => 'test',
            'body' => 'fake',
        ]);
        $item->update([
            'body' => '<iframe src="http://google.com"/>',
        ]);

        $item->refresh();

        $this->assertEquals('', $item->body);
    }

    /**
     * @group smoke
     */
    public function testCreatingModelNotCleanNotInList()
    {
        $html = '<script>1235</script>test';
        $item = DirtyPost::create([
            'title' => 'test',
            'body' => $html,
            'description' => $html,
        ]);
        $item->refresh();

        $this->assertEquals('test', $item->body);
        $this->assertEquals($html, $item->description);
    }
}

/**
 * DirtyPost
 */
class DirtyPost extends Model
{
    use Sanitized;

    public $table = 'dirty_posts';

    public $fillable = [
        'title',
        'body',
        'description',
    ];

    protected array $fieldsToSanitize = [
        'body',
    ];
}
