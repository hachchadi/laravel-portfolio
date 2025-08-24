<?php

namespace Tests\Feature;

use Tests\TestCase;
use Livewire\Livewire;
use App\Livewire\ContactForm;

class ContactFormComponentTest extends TestCase
{
    /** @test */
    public function it_renders_contact_form_component()
    {
        Livewire::test(ContactForm::class)
            ->assertStatus(200)
            ->assertSee('Get In Touch')
            ->assertSee('Name')
            ->assertSee('Email')
            ->assertSee('Subject')
            ->assertSee('Message')
            ->assertSee('Send Message');
    }

    /** @test */
    public function it_can_trigger_animation_start()
    {
        Livewire::test(ContactForm::class)
            ->assertSet('animateOnLoad', false)
            ->call('startAnimation')
            ->assertSet('animateOnLoad', true);
    }

    /** @test */
    public function it_can_reset_form()
    {
        Livewire::test(ContactForm::class)
            ->set('name', 'John Doe')
            ->set('email', 'john@example.com')
            ->set('subject', 'Test Subject')
            ->set('message', 'Test message content')
            ->set('isSubmitted', true)
            ->call('resetForm')
            ->assertSet('name', '')
            ->assertSet('email', '')
            ->assertSet('subject', '')
            ->assertSet('message', '')
            ->assertSet('isSubmitted', false);
    }

    /** @test */
    public function it_validates_required_fields()
    {
        Livewire::test(ContactForm::class)
            ->call('submit')
            ->assertHasErrors(['name', 'email', 'subject', 'message']);
    }

    /** @test */
    public function it_validates_email_format()
    {
        Livewire::test(ContactForm::class)
            ->set('email', 'invalid-email')
            ->call('submit')
            ->assertHasErrors(['email']);
    }

    /** @test */
    public function it_validates_minimum_lengths()
    {
        Livewire::test(ContactForm::class)
            ->set('name', 'A')
            ->set('subject', 'Hi')
            ->set('message', 'Short')
            ->call('submit')
            ->assertHasErrors(['name', 'subject', 'message']);
    }

    /** @test */
    public function it_validates_maximum_lengths()
    {
        Livewire::test(ContactForm::class)
            ->set('name', str_repeat('a', 256))
            ->set('email', str_repeat('a', 250) . '@example.com')
            ->set('subject', str_repeat('a', 256))
            ->set('message', str_repeat('a', 2001))
            ->call('submit')
            ->assertHasErrors(['name', 'email', 'subject', 'message']);
    }

    /** @test */
    public function it_includes_alpine_js_animation_attributes()
    {
        Livewire::test(ContactForm::class)
            ->assertSeeHtml('x-data')
            ->assertSeeHtml('x-show="isVisible"')
            ->assertSeeHtml('x-transition:enter')
            ->assertSeeHtml('x-init');
    }

    /** @test */
    public function it_includes_intersection_observer_for_scroll_animations()
    {
        Livewire::test(ContactForm::class)
            ->assertSeeHtml('IntersectionObserver')
            ->assertSeeHtml('threshold: 0.2');
    }

    /** @test */
    public function it_includes_success_message_functionality()
    {
        Livewire::test(ContactForm::class)
            ->assertSeeHtml('x-show="isSubmitted"')
            ->assertSeeHtml('Message Sent Successfully!')
            ->assertSeeHtml('auto-hide-success');
    }

    /** @test */
    public function it_includes_loading_states()
    {
        Livewire::test(ContactForm::class)
            ->assertSeeHtml('wire:loading')
            ->assertSeeHtml('Sending...')
            ->assertSeeHtml('animate-spin');
    }

    /** @test */
    public function it_includes_form_validation_styling()
    {
        Livewire::test(ContactForm::class)
            ->assertSeeHtml('@error')
            ->assertSeeHtml('border-red-500')
            ->assertSeeHtml('text-red-600');
    }

    /** @test */
    public function it_includes_character_counter()
    {
        Livewire::test(ContactForm::class)
            ->assertSeeHtml('{{ strlen($message) }}/2000 characters');
    }

    /** @test */
    public function it_includes_accessibility_features()
    {
        Livewire::test(ContactForm::class)
            ->assertSeeHtml('for="name"')
            ->assertSeeHtml('for="email"')
            ->assertSeeHtml('for="subject"')
            ->assertSeeHtml('for="message"')
            ->assertSeeHtml('autocomplete="name"')
            ->assertSeeHtml('autocomplete="email"')
            ->assertSeeHtml('aria-');
    }

    /** @test */
    public function it_includes_security_features()
    {
        Livewire::test(ContactForm::class)
            ->assertSeeHtml('wire:submit="submit"')
            ->assertSeeHtml('Your information is secure');
    }

    /** @test */
    public function it_includes_responsive_design_classes()
    {
        Livewire::test(ContactForm::class)
            ->assertSeeHtml('grid grid-cols-1 md:grid-cols-2')
            ->assertSeeHtml('max-w-2xl mx-auto')
            ->assertSeeHtml('md:grid-cols-3');
    }

    /** @test */
    public function it_includes_dark_mode_classes()
    {
        Livewire::test(ContactForm::class)
            ->assertSeeHtml('dark:text-white')
            ->assertSeeHtml('dark:bg-gray-800')
            ->assertSeeHtml('dark:border-gray-600');
    }

    /** @test */
    public function it_includes_contact_information_cards()
    {
        Livewire::test(ContactForm::class)
            ->assertSee('Email')
            ->assertSee('Response Time')
            ->assertSee('Location')
            ->assertSee('Within 24 hours')
            ->assertSee('Available Remotely');
    }

    /** @test */
    public function it_includes_form_buttons()
    {
        Livewire::test(ContactForm::class)
            ->assertSeeHtml('wire:click="resetForm"')
            ->assertSee('Reset')
            ->assertSee('Send Message');
    }

    /** @test */
    public function it_includes_real_time_validation()
    {
        Livewire::test(ContactForm::class)
            ->assertSeeHtml('wire:model.live.debounce.300ms');
    }

    /** @test */
    public function it_includes_proper_form_structure()
    {
        Livewire::test(ContactForm::class)
            ->assertSeeHtml('<form')
            ->assertSeeHtml('type="text"')
            ->assertSeeHtml('type="email"')
            ->assertSeeHtml('<textarea')
            ->assertSeeHtml('type="submit"');
    }

    /** @test */
    public function it_includes_required_field_indicators()
    {
        Livewire::test(ContactForm::class)
            ->assertSeeHtml('<span class="text-red-500">*</span>')
            ->assertSee('Required fields');
    }

    /** @test */
    public function it_includes_placeholder_text()
    {
        Livewire::test(ContactForm::class)
            ->assertSeeHtml('placeholder="Your full name"')
            ->assertSeeHtml('placeholder="your.email@example.com"')
            ->assertSeeHtml('placeholder="What\'s this about?"')
            ->assertSeeHtml('placeholder="Tell me about your project');
    }

    /** @test */
    public function it_includes_staggered_animations()
    {
        Livewire::test(ContactForm::class)
            ->assertSeeHtml('delay-100')
            ->assertSeeHtml('delay-200')
            ->assertSeeHtml('delay-300')
            ->assertSeeHtml('delay-500');
    }
}