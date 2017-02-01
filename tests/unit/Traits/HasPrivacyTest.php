<?php

namespace Tests\unit\Traits;


use App\User;
use App\Privacy;
use Illuminate\Database\Eloquent\Builder;

class HasPrivacyTest extends TestCase
{
    /** @test */
    function priding_a_privacy_string_checks_the_string_against_the_model_attribute()
    {
        $model = new TestPrivacy(['privacy' => Privacy::PRIVATE]);

        $this->assertTrue($model->privacy('private'));
        $this->assertFalse($model->privacy('public'));
        $this->assertFalse($model->privacy('unlisted'));
    }

    /** @test */
    function displayable_scopes_only_public_records_when_no_user_is_provied()
    {
        $model = new TestPrivacy();
        $mockedQueryBuilder = Mockery::mock('Illuminate\Database\Query\Builder');

        $mockedQueryBuilder->shouldReceive('where')->withArgs(['privacy', 'public'])->once();

        $model->scopeWithoutPrivacy(new Builder($mockedQueryBuilder), null);
    }

    /** @test */
    function displayable_scope_does_not_limit_records_when_a_valid_user_is_provided()
    {
        $model = new TestPrivacy();
        $mockedQueryBuilder = Mockery::mock('Illuminate\Database\Query\Builder');

        $mockedQueryBuilder->shouldNotReceive('where');

        $model->scopeWithoutPrivacy(new Builder($mockedQueryBuilder), true);
    }
}
