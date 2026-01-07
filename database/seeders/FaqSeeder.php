<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faq;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            // Orders
            [
                'category' => 'Orders',
                'question' => 'How do I place an order?',
                'answer' => 'Browse our products, add items to your cart, and proceed to checkout. You can choose between pickup or delivery, and pay at the store or via online banking.',
                'sort_order' => 1,
            ],
            [
                'category' => 'Orders',
                'question' => 'Can I cancel my order?',
                'answer' => 'Yes, you can cancel your order as long as it\'s still in "Pending" status. Go to My Orders, select the order, and click "Cancel Order".',
                'sort_order' => 2,
            ],
            [
                'category' => 'Orders',
                'question' => 'How can I track my order status?',
                'answer' => 'Visit "My Orders" from the sidebar menu. You\'ll see all your orders with their current status: Pending, Processing, Out for Delivery, Completed, or Cancelled.',
                'sort_order' => 3,
            ],

            // Payment
            [
                'category' => 'Payment',
                'question' => 'What payment methods do you accept?',
                'answer' => "We accept two payment methods:\n• Pay at Store - Pay cash when you pick up your order\n• Online Banking - Transfer payment and upload the receipt",
                'sort_order' => 1,
            ],
            [
                'category' => 'Payment',
                'question' => 'How do I pay via online banking?',
                'answer' => 'During checkout, select "Online Banking" and upload a screenshot of your payment transfer. Our team will verify the payment and update your order status.',
                'sort_order' => 2,
            ],

            // Delivery
            [
                'category' => 'Delivery',
                'question' => 'Do you deliver to my area?',
                'answer' => 'We currently deliver within Mahallah Bilal and nearby areas in IIUM Gombak campus. For other locations, please contact us.',
                'sort_order' => 1,
            ],
            [
                'category' => 'Delivery',
                'question' => 'How long does delivery take?',
                'answer' => 'Delivery usually takes 1-2 hours within campus during operating hours. You\'ll receive a notification when your order is out for delivery.',
                'sort_order' => 2,
            ],
            [
                'category' => 'Delivery',
                'question' => 'Can I pick up my order instead?',
                'answer' => 'Yes! Select "Pickup" during checkout. We\'ll notify you when your order is ready for pickup at our store in Mahallah Bilal.',
                'sort_order' => 3,
            ],

            // Account
            [
                'category' => 'Account',
                'question' => 'How do I create an account?',
                'answer' => 'Click "Login" in the sidebar, then click "Create Account". Fill in your details and you\'re good to go!',
                'sort_order' => 1,
            ],
            [
                'category' => 'Account',
                'question' => 'I forgot my password. What should I do?',
                'answer' => 'On the login page, click "Forgot Password?". Enter your email and we\'ll send you a verification code to reset your password.',
                'sort_order' => 2,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create(array_merge($faq, ['is_active' => true]));
        }
    }
}
