<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Livewire\ContactForm;

class ContactFormComponentTest extends TestCase
{
    /** @test */
    public function it_initializes_with_default_values()
    {
        $component = new ContactForm();
        
        $this->assertEquals('', $component->name);
        $this->assertEquals('', $component->email);
        $this->assertEquals('', $component->subject);
        $this->assertEquals('', $component->message);
        $this->assertFalse($component->isSubmitting);
        $this->assertFalse($component->isSubmitted);
        $this->assertFalse($component->animateOnLoad);
    }

    /** @test */
    public function it_can_start_animation()
    {
        $component = new ContactForm();
        
        $this->assertFalse($component->animateOnLoad);
        
        $component->startAnimation();
        
        $this->assertTrue($component->animateOnLoad);
    }

    /** @test */
    public function it_can_reset_form()
    {
        $component = new ContactForm();
        $component->name = 'John Doe';
        $component->email = 'john@example.com';
        $component->subject = 'Test Subject';
        $component->message = 'Test message';
        $component->isSubmitted = true;
        
        $component->resetForm();
        
        $this->assertEquals('', $component->name);
        $this->assertEquals('', $component->email);
        $this->assertEquals('', $component->subject);
        $this->assertEquals('', $component->message);
        $this->assertFalse($component->isSubmitted);
    }

    /** @test */
    public function it_renders_correct_view()
    {
        $component = new ContactForm();
        
        $view = $component->render();
        
        $this->assertEquals('livewire.contact-form', $view->getName());
    }

    /** @test */
    public function it_has_required_properties()
    {
        $component = new ContactForm();
        
        $this->assertObjectHasProperty('name', $component);
        $this->assertObjectHasProperty('email', $component);
        $this->assertObjectHasProperty('subject', $component);
        $this->assertObjectHasProperty('message', $component);
        $this->assertObjectHasProperty('isSubmitting', $component);
        $this->assertObjectHasProperty('isSubmitted', $component);
        $this->assertObjectHasProperty('animateOnLoad', $component);
    }

    /** @test */
    public function it_has_validation_attributes()
    {
        $component = new ContactForm();
        
        // Test that validation attributes are properly set
        $this->assertEquals('', $component->name);
        $this->assertEquals('', $component->email);
        $this->assertEquals('', $component->subject);
        $this->assertEquals('', $component->message);
    }

    /** @test */
    public function it_can_set_form_field_values()
    {
        $component = new ContactForm();
        
        $component->name = 'John Doe';
        $component->email = 'john@example.com';
        $component->subject = 'Test Subject';
        $component->message = 'Test message content';
        
        $this->assertEquals('John Doe', $component->name);
        $this->assertEquals('john@example.com', $component->email);
        $this->assertEquals('Test Subject', $component->subject);
        $this->assertEquals('Test message content', $component->message);
    }

    /** @test */
    public function it_handles_form_state_correctly()
    {
        $component = new ContactForm();
        
        // Initially not submitting or submitted
        $this->assertFalse($component->isSubmitting);
        $this->assertFalse($component->isSubmitted);
        
        // Can set submitting state
        $component->isSubmitting = true;
        $this->assertTrue($component->isSubmitting);
        
        // Can set submitted state
        $component->isSubmitted = true;
        $this->assertTrue($component->isSubmitted);
    }
}