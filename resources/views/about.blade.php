@extends('layouts.app')

@section('title', 'About Us')

@section('content')
<style>
    .about-hero-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        align-items: center;
    }
    .about-contact-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
    }
    .about-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    .about-team-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 20px;
        text-align: center;
    }
    .about-stats {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }
    @media (max-width: 768px) {
        .about-hero-grid {
            grid-template-columns: 1fr;
            gap: 25px;
        }
        .about-hero-grid > div:first-child {
            order: 1;
        }
        .about-hero-grid > div:last-child {
            order: 0;
        }
        .about-contact-grid {
            grid-template-columns: 1fr;
        }
        .about-form-grid {
            grid-template-columns: 1fr;
        }
        .about-team-grid {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }
        .about-team-grid > div {
            flex: 1;
            min-width: 0;
        }
        .about-team-grid .team-icon {
            width: 40px !important;
            height: 40px !important;
            font-size: 14px !important;
        }
        .about-team-grid .team-name {
            font-size: 11px !important;
        }
        .about-team-grid .team-id {
            font-size: 9px !important;
        }
        .about-stats {
            justify-content: center;
        }
    }
</style>

<div class="page-header">
    <h1 class="page-title">About Leafé Mart</h1>
</div>

<!-- Hero Section -->
<div style="background: linear-gradient(135deg, #1E3A5F 0%, #2D5A87 50%, #1E3A5F 100%); border-radius: 24px; padding: 40px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <!-- Decorative Elements -->
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.03); border-radius: 50%;"></div>
    
    <div class="about-hero-grid" style="position: relative; z-index: 1;">
        <div>
            <div style="display: inline-block; background: rgba(255,255,255,0.15); color: white; padding: 8px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; margin-bottom: 20px; border: 1px solid rgba(255,255,255,0.3);">
                <i class="fas fa-store"></i> Official Campus Store
            </div>
            <h2 style="color: white; margin-bottom: 15px; font-size: 32px; line-height: 1.3;">Mahallah Bilal<br>Online Store</h2>
            <p style="color: rgba(255,255,255,0.85); line-height: 1.8; margin-bottom: 15px; font-size: 15px;">
                Leafé Mart is the official online store for Mahallah Bilal residents and IIUM students. 
                We provide a convenient way to shop for everyday essentials without leaving your mahallah.
            </p>
            <p style="color: rgba(255,255,255,0.75); line-height: 1.8; margin-bottom: 25px; font-size: 14px;">
                Our mission is to digitalize the traditional shopping experience, offering real-time stock 
                availability, easy ordering, and hassle-free pickup.
            </p>
            <div class="about-stats">
                <div style="padding: 15px 25px; background: rgba(255,255,255,0.15); border-radius: 16px; text-align: center; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1);">
                    <div style="font-size: 28px; font-weight: 700; color: white;">100+</div>
                    <div style="color: rgba(255,255,255,0.8); font-size: 12px;">Products</div>
                </div>
                <div style="padding: 15px 25px; background: rgba(255,255,255,0.15); border-radius: 16px; text-align: center; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1);">
                    <div style="font-size: 28px; font-weight: 700; color: white;">5</div>
                    <div style="color: rgba(255,255,255,0.8); font-size: 12px;">Categories</div>
                </div>
                <div style="padding: 15px 25px; background: rgba(255,255,255,0.15); border-radius: 16px; text-align: center; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1);">
                    <div style="font-size: 28px; font-weight: 700; color: white;">24/7</div>
                    <div style="color: rgba(255,255,255,0.8); font-size: 12px;">Online Ordering</div>
                </div>
            </div>
        </div>
        <div style="border-radius: 20px; overflow: hidden; height: 320px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); border: 4px solid rgba(255,255,255,0.2);">
            <img src="{{ asset('images/store.jpg') }}" alt="Leafé Mart Store" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
    </div>
</div>

<!-- Why Choose Leafé Mart -->
<div class="card" style="margin-bottom: 30px;">
    <h3 style="color: var(--primary-dark); margin-bottom: 25px; text-align: center;"><i class="fas fa-check-circle"></i> Why Choose Leafé Mart?</h3>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
        <div style="background: linear-gradient(135deg, #E3F2FD, #BBDEFB); border-radius: 16px; padding: 25px; text-align: center;">
            <div style="width: 60px; height: 60px; background: var(--primary); border-radius: 50%; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px;">
                <i class="fas fa-clock"></i>
            </div>
            <h4 style="color: var(--primary-dark); margin-bottom: 10px;">24/7 Availability</h4>
            <p style="color: var(--gray-600); font-size: 14px; line-height: 1.6;">Order anytime, anywhere. Our platform is available round the clock for your convenience.</p>
        </div>
        
        <div style="background: linear-gradient(135deg, #E8F5E9, #C8E6C9); border-radius: 16px; padding: 25px; text-align: center;">
            <div style="width: 60px; height: 60px; background: #4CAF50; border-radius: 50%; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px;">
                <i class="fas fa-box-open"></i>
            </div>
            <h4 style="color: var(--primary-dark); margin-bottom: 10px;">Real-time Stock</h4>
            <p style="color: var(--gray-600); font-size: 14px; line-height: 1.6;">Check product availability instantly. No more walking to find out items are out of stock.</p>
        </div>
        
        <div style="background: linear-gradient(135deg, #FFF3E0, #FFE0B2); border-radius: 16px; padding: 25px; text-align: center;">
            <div style="width: 60px; height: 60px; background: #FF9800; border-radius: 50%; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px;">
                <i class="fas fa-walking"></i>
            </div>
            <h4 style="color: var(--primary-dark); margin-bottom: 10px;">Easy Pickup</h4>
            <p style="color: var(--gray-600); font-size: 14px; line-height: 1.6;">Order online, pick up at your convenience. Skip the queue and save time.</p>
        </div>
        
        <div style="background: linear-gradient(135deg, #FCE4EC, #F8BBD9); border-radius: 16px; padding: 25px; text-align: center;">
            <div style="width: 60px; height: 60px; background: #E91E63; border-radius: 50%; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px;">
                <i class="fas fa-motorcycle"></i>
            </div>
            <h4 style="color: var(--primary-dark); margin-bottom: 10px;">Delivery Available</h4>
            <p style="color: var(--gray-600); font-size: 14px; line-height: 1.6;">Get your orders delivered right to your doorstep within the mahallah area.</p>
        </div>
    </div>
</div>

<div class="about-contact-grid">
    <!-- Contact Info -->
    <div class="card">
        <h3 style="color: var(--primary-dark); margin-bottom: 20px;"><i class="fas fa-phone-alt"></i> Contact Us</h3>
        
        <div style="display: flex; flex-direction: column; gap: 15px;">
            <a href="mailto:leafemart@iium.edu.my" style="display: flex; align-items: center; gap: 15px; text-decoration: none; padding: 10px; border-radius: 12px; transition: all 0.3s ease;" class="contact-link">
                <div style="width: 45px; height: 45px; background: #EA4335; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; flex-shrink: 0;">
                    <i class="fas fa-envelope" style="font-size: 18px;"></i>
                </div>
                <div style="min-width: 0;">
                    <div style="font-weight: 500; color: var(--primary-dark);">Email</div>
                    <div style="color: var(--gray-400); word-break: break-word;">leafemart@iium.edu.my</div>
                </div>
            </a>

            <a href="tel:+60123456789" style="display: flex; align-items: center; gap: 15px; text-decoration: none; padding: 10px; border-radius: 12px; transition: all 0.3s ease;" class="contact-link">
                <div style="width: 45px; height: 45px; background: var(--primary); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; flex-shrink: 0;">
                    <i class="fas fa-phone-alt" style="font-size: 18px;"></i>
                </div>
                <div>
                    <div style="font-weight: 500; color: var(--primary-dark);">Phone</div>
                    <div style="color: var(--gray-400);">+60 12-345 6789</div>
                </div>
            </a>

            <a href="https://wa.me/60123456789" target="_blank" style="display: flex; align-items: center; gap: 15px; text-decoration: none; padding: 10px; border-radius: 12px; transition: all 0.3s ease;" class="contact-link">
                <div style="width: 45px; height: 45px; background: #25D366; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; flex-shrink: 0;">
                    <i class="fab fa-whatsapp" style="font-size: 24px;"></i>
                </div>
                <div>
                    <div style="font-weight: 500; color: var(--primary-dark);">WhatsApp</div>
                    <div style="color: var(--gray-400);">+60 12-345 6789</div>
                </div>
            </a>
        </div>
        
        <style>
            .contact-link:hover {
                background: var(--primary-light);
            }
        </style>
    </div>

    <!-- Location -->
    <div class="card">
        <h3 style="color: var(--primary-dark); margin-bottom: 20px;"><i class="fas fa-map-marker-alt"></i> Location</h3>
        
        <div style="display: flex; align-items: start; gap: 15px; margin-bottom: 20px;">
            <div style="width: 45px; height: 45px; background: var(--primary-light); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--primary); flex-shrink: 0;">
                <i class="fas fa-building"></i>
            </div>
            <div style="min-width: 0;">
                <div style="font-weight: 500;">Leafé Mart</div>
                <div style="color: var(--gray-400); word-break: break-word;">Mahallah Bilal, International Islamic University Malaysia, 53100 Gombak, Selangor</div>
            </div>
        </div>

        <div style="border-radius: 12px; overflow: hidden; margin-bottom: 15px;">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3983.5424!2d101.7356!3d3.2516!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cc3843bfb6a031%3A0x2dc5e067aae9f8a4!2sMahallah%20Bilal!5e0!3m2!1sen!2smy!4v1703243641000!5m2!1sen!2smy" 
                width="100%" 
                height="150" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
        
        <a href="https://maps.app.goo.gl/A6JsNZJggtMg4WuJA" target="_blank" class="btn btn-primary" style="width: 100%; text-align: center;">
            <i class="fas fa-map-marker-alt"></i> View on Google Maps
        </a>
    </div>
</div>

<!-- Contact Form -->
<div id="contact-form" class="card" style="margin-top: 30px;">
    <h3 style="color: var(--primary-dark); margin-bottom: 20px;"><i class="fas fa-envelope"></i> Send Us a Message</h3>
    <p style="color: var(--gray-600); margin-bottom: 20px;">Have a question or feedback? Send us a message and we'll get back to you soon!</p>
    
    <form action="{{ route('contact.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="about-form-grid" style="margin-bottom: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--primary-dark);">Name</label>
                <input type="text" name="name" class="form-control" placeholder="Your Name" 
                    value="{{ auth()->check() ? auth()->user()->name : old('name') }}" required>
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--primary-dark);">Email</label>
                <input type="email" name="email" class="form-control" placeholder="your@email.com" 
                    value="{{ auth()->check() ? auth()->user()->email : old('email') }}" required>
            </div>
        </div>
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--primary-dark);">Subject</label>
            <input type="text" name="subject" class="form-control" placeholder="What is this about?" value="{{ old('subject') }}" required>
        </div>
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--primary-dark);">Message</label>
            <textarea name="message" class="form-control" rows="5" placeholder="Type your message here..." required>{{ old('message') }}</textarea>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 500; color: var(--primary-dark);">Attach Images (Optional)</label>
            <input type="file" name="images[]" accept="image/*" multiple class="form-control" style="padding: 8px;">
            <small style="color: var(--gray-400);">You can select multiple images. Max 2MB each.</small>
        </div>
        
        <button type="submit" class="btn btn-primary" style="width: 100%;">
            <i class="fas fa-paper-plane"></i> Send Message
        </button>
    </form>
</div>

<!-- Team -->
<div class="card" style="margin-top: 30px;">
    <h3 style="color: var(--primary-dark); margin-bottom: 20px; text-align: center;"><i class="fas fa-users"></i> Development Team</h3>
    
    <div class="about-team-grid">
        <div>
            <div class="team-icon" style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border-radius: 50%; margin: 0 auto 10px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">G</div>
            <div class="team-name" style="font-weight: 500;">Ghassan</div>
            <div class="team-id" style="color: var(--gray-400); font-size: 12px;">2112819</div>
        </div>
        <div>
            <div class="team-icon" style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border-radius: 50%; margin: 0 auto 10px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">A</div>
            <div class="team-name" style="font-weight: 500;">Ahmad Danish</div>
            <div class="team-id" style="color: var(--gray-400); font-size: 12px;">2310789</div>
        </div>
        <div>
            <div class="team-icon" style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border-radius: 50%; margin: 0 auto 10px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">N</div>
            <div class="team-name" style="font-weight: 500;">Naila Saleem</div>
            <div class="team-id" style="color: var(--gray-400); font-size: 12px;">2312934</div>
        </div>
        <div>
            <div class="team-icon" style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border-radius: 50%; margin: 0 auto 10px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">I</div>
            <div class="team-name" style="font-weight: 500;">Idham Zakwan</div>
            <div class="team-id" style="color: var(--gray-400); font-size: 12px;">2318121</div>
        </div>
        <div>
            <img class="team-icon" src="{{ asset('images/team/aqil.jpg') }}" alt="Muhamad Aqil" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 2px solid var(--primary); margin: 0 auto 10px; display: block;">
            <div class="team-name" style="font-weight: 500;">Muhamad Aqil</div>
            <div class="team-id" style="color: var(--gray-400); font-size: 12px;">2215761</div>
        </div>
    </div>
</div>
@endsection
