<?php

namespace Tests\Feature\Admin;

use App\Filament\Resources\EnquiryResource;
use App\Filament\Resources\EnquiryResource\Pages\EditEnquiry;
use App\Filament\Resources\EnquiryResource\Pages\ListEnquiries;
use App\Filament\Resources\MenuItemResource\Pages\EditMenuItem;
use App\Filament\Resources\SliderResource\Pages\EditSlider;
use App\Models\Enquiry;
use App\Models\MenuItem;
use App\Models\Slide;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->admin()->create();
    }

    public function test_non_admin_cannot_access_the_panel(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)->get('/admin')->assertForbidden();
    }

    public function test_admin_can_access_the_dashboard(): void
    {
        $this->actingAs($this->admin())->get('/admin')->assertOk();
    }

    /** Regression: parent_id select used to be wired to the `children` relation instead of `parent`. */
    public function test_menu_item_parent_can_be_set_and_saved(): void
    {
        $this->actingAs($this->admin());

        $parent = MenuItem::create(['menu' => 'main', 'label' => 'Shop', 'url' => '/products']);
        $child  = MenuItem::create(['menu' => 'main', 'label' => 'Black Teas', 'url' => '/product-ranges/black-teas']);

        Livewire::test(EditMenuItem::class, ['record' => $child->getRouteKey()])
            ->fillForm(['parent_id' => $parent->id])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame($parent->id, $child->fresh()->parent_id);
        $this->assertTrue($child->fresh()->parent->is($parent));
    }

    /** Regression: enquiries manually created via /admin used to hit NOT NULL constraints since every field was disabled. */
    public function test_enquiries_cannot_be_manually_created(): void
    {
        $this->assertFalse(EnquiryResource::canCreate());
        $this->assertArrayNotHasKey('create', EnquiryResource::getPages());

        $this->actingAs($this->admin());

        Livewire::test(ListEnquiries::class)->assertActionDoesNotExist('create');
    }

    public function test_enquiry_can_be_marked_handled_from_the_edit_page(): void
    {
        $this->actingAs($this->admin());

        $enquiry = Enquiry::create([
            'name' => 'Buyer', 'email' => 'buyer@example.com',
            'message' => 'Requesting a quote for bulk Ceylon tea.',
        ]);

        Livewire::test(EditEnquiry::class, ['record' => $enquiry->getRouteKey()])
            ->fillForm(['handled_at' => now()])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertNotNull($enquiry->fresh()->handled_at);
    }

    /** Regression: Slider::slides() is scoped to is_active=true, which the admin repeater used directly. */
    public function test_inactive_slides_stay_queryable_for_admin_but_hidden_on_the_front_end(): void
    {
        $slider = Slider::create(['slug' => 'home-hero', 'name' => 'Home hero']);
        $active = Slide::create(['slider_id' => $slider->id, 'heading' => 'Active', 'is_active' => true, 'sort' => 0]);
        $hidden = Slide::create(['slider_id' => $slider->id, 'heading' => 'Hidden', 'is_active' => false, 'sort' => 1]);

        $this->assertTrue($slider->slides->pluck('id')->contains($active->id));
        $this->assertFalse($slider->slides->pluck('id')->contains($hidden->id));

        $this->assertTrue($slider->allSlides->pluck('id')->contains($active->id));
        $this->assertTrue($slider->allSlides->pluck('id')->contains($hidden->id));
    }

    public function test_saving_the_slider_form_does_not_delete_inactive_slides(): void
    {
        $this->actingAs($this->admin());

        $slider = Slider::create(['slug' => 'home-hero', 'name' => 'Home hero']);
        $hidden = Slide::create(['slider_id' => $slider->id, 'heading' => 'Hidden', 'is_active' => false, 'sort' => 0]);

        Livewire::test(EditSlider::class, ['record' => $slider->getRouteKey()])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertNotNull($hidden->fresh());
    }
}
