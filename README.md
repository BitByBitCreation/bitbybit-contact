# BitByBit Contact — Smart Message Analysis & Response Drafting

A lightweight Laravel 11 project that connects a basic contact form with AI-powered analysis and automated response drafting.  
Ideal as a starting point or showcase for AI integration in modern PHP apps.

---

## ✅ Features

- 🧠 **AI analysis** (sentiment, keywords, summary, toxicity, spam score)
- 📨 **Auto-generated response drafts** via OpenAI (or compatible API)
- ⚙️ **Modern Laravel architecture** (DTOs, Events, Listeners)
- 💌 **Responsive email templates** (confirmation + internal)
- 🧩 **Extensible structure** (e.g. queueing, logging, fallback strategies)
- 📁 **Clean structure** with separation of concerns

---

## ⚙️ Tech Stack

- PHP 8.2+
- Laravel 11
- OpenAI API (or similar)
- Blade templates
- Laravel Mailables (modern `envelope()` / `content()` API)

---

## 📋 Requirements

- PHP 8.2+
- Composer
- OpenAI API key
- Mail configuration (SMTP/etc.)
- Laravel 11 compatible environment

---

## 📁 Structure Overview

```
app/
├── DTO/               → Data containers (incoming message & analysis result)
├── Enums/             → Enum types (e.g. analysis fields)
├── Events/            → MessageReceived & MessageAnalyzed
├── Listeners/         → Handle AI analysis & email notifications
├── Mail/              → Laravel Mailable classes
├── Services/          → AI service and client abstraction
├── Utils/             → JSON extraction helper
resources/views/emails/
├── combined_contact.blade.php   → Internal email with message + AI draft
├── confirmation.blade.php       → Auto-confirmation for sender
```

---

## 🚀 Setup

1. **Clone and install:**
   ```bash
   git clone https://github.com/BitByBitCreation/bitbybit-contact.git
   cd bitbybit-contact
   composer install
   cp .env.example .env
   php artisan key:generate
   ```

2. **Configure environment:**
   
   Update your `.env`:
   ```env
   OPENAI_API_KEY=your-key
   ADMIN_MAIL_ADDRESS=you@example.com
   
   # Mail configuration
   MAIL_MAILER=smtp
   MAIL_HOST=your-smtp-host
   MAIL_PORT=587
   MAIL_USERNAME=your-email@example.com
   MAIL_PASSWORD=your-password
   MAIL_ENCRYPTION=tls
   ```

3. **Configure services:**
   
   Make sure your `config/services.php` contains:
   ```php
   'openai' => [
       'key' => env('OPENAI_API_KEY'),
   ],
   ```

   And your `config/mail.php` contains:
   ```php
   'admin_address' => env('ADMIN_MAIL_ADDRESS', 'you@example.com'),
   ```

4. **Start the application:**
   ```bash
   php artisan serve
   ```

---

## 📖 Usage Example

**Basic contact form route:**
```php
// routes/web.php
Route::get('/contact', [ContactController::class, 'showForm']);
Route::post('/contact', [ContactController::class, 'contactSubmit']);
```

**Controller implementation:**
```php
public function contactSubmit(Request $request): RedirectResponse
{
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'message' => 'required|string|max:5000',
        'subject' => 'required|string|max:255',
    ]);

    $dto = new MessageAnalysisDTO(
        subject: $data['subject'],
        message: $data['message'],
        sender: $data['name'],
        email: $data['email'],
    );

    try {
        MessageReceived::dispatch($dto);
        return redirect()->back()->with('success', trans('messages.contact_form_submitted'));
    } catch (\Exception $e) {
        \Log::error('Failed to dispatch MessageReceived event', ['error' => $e->getMessage()]);
        return redirect()->back()->with('error', trans('messages.submission_failed'));
    }
}
```

**Sample contact form (Blade):**
```html
<form method="POST" action="/contact">
    @csrf
    <div>
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required>
    </div>
    <div>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
    </div>
    <div>
        <label for="subject">Subject:</label>
        <input type="text" name="subject" id="subject" required>
    </div>
    <div>
        <label for="message">Message:</label>
        <textarea name="message" id="message" required></textarea>
    </div>
    <button type="submit">Send Message</button>
</form>
```

---

## 🧪 How It Works

1. Contact form submission triggers `MessageReceived` event  
2. A listener sends the message to an AI service and parses the result  
3. If not classified as spam → `MessageAnalyzed` is dispatched  
4. A combined email is sent (original message + AI-generated response draft)  
5. The user receives a confirmation email with summary and contact details  

---

## 🔗 Works great with

- **[bitbybit-chatbot](https://github.com/BitByBitCreation/bitbybit-chatbot)** → Complete customer communication suite

---

## 🧠 AI Analysis Output

The AI service analyzes incoming messages and returns structured data:

```json
{
  "sentiment": "positive",
  "keywords": ["support", "integration", "help"],
  "summary": "Customer requesting help with API integration",
  "toxicity_score": 0,
  "spam_score": 0,
  "suggested_response": "Thank you for reaching out regarding API integration. We'd be happy to help you with your implementation..."
}
```

**Analysis Fields:**
- **Sentiment**: positive, negative, neutral
- **Keywords**: Extracted key terms from the message
- **Summary**: Brief overview of the message content
- **Toxicity Score**: 0-100 (lower is better)
- **Spam Score**: 0-100 (lower is better)
- **Suggested Response**: AI-generated draft response

---

## 🔒 Security Notes

- Never commit your OpenAI API key to version control
- Configure rate limiting for the contact form
- Validate and sanitize all user inputs
- Consider implementing CAPTCHA for spam protection
- Use HTTPS in production environments
- Regularly rotate API keys

---

## 🐛 Troubleshooting

**Common Issues:**

**OpenAI API errors:**
```bash
# Check your API key
php artisan tinker
>>> config('services.openai.key')
```

**Email not sending:**
- Verify SMTP configuration in `.env`
- Check Laravel logs: `storage/logs/laravel.log`
- Test with `php artisan tinker` and `Mail::raw('test', function($msg) { $msg->to('test@example.com'); })`

**Event not triggering:**
- Check if events are being dispatched: add logging to listeners

---

## 💡 Notes

- This is a backend-only demo – a simple contact form route/controller can be added easily
- All processing is synchronous by default, but ready for queue integration
- No database dependency required (stateless design)
- Consider implementing queues for production environments with high traffic

---

## 🧠 Suggestions to Improve AI Response Quality

This project is designed to be lightweight and easily extensible. For improved analysis results, especially in production environments, you may consider:

- **Stopword Filtering** – Remove common words before analysis to improve keyword relevance
- **Domain Context Injection** – Add context-specific knowledge to guide the AI's understanding (e.g. glossary terms, FAQs)
- **Custom Prompt Engineering** – Fine-tune prompts for your specific use case
- **Response Templates** – Create templates for common inquiry types

✅ These techniques have already been demonstrated in a related project:  
👉 [bitbybit-chatbot: Knowledge-based NLP Enhancements](https://github.com/BitByBitCreation/bitbybit-chatbot)

Feel free to explore and integrate them into this project as needed.

---

## 📜 License

MIT – feel free to use and adapt.

---

**Demo-oriented project structure**  
Perfect for showcasing Laravel event-driven logic and AI integration.