# AI ChatBot (OpenAI File Search, PHP)

A minimal, secure PHP chatbot app that connects to an existing OpenAI Assistant with file search enabled.

## 🚀 Quick Deploy (Ubuntu Apache2)

1. **Clone/download this project:**
   ```
   git clone https://github.com/youruser/ai-chatbot.git
   cd ai-chatbot
   ```

2. **Install PHP dependencies (Composer required):**
   ```
   composer install
   ```

3. **Set up your environment:**
   - Copy `.env.example` to `.env`
   - Put your OpenAI API key and Assistant ID in `.env`

4. **Point Apache to `/public` as your DocumentRoot:**
   ```
   # Example Apache config:
   <VirtualHost *:80>
     ServerName your-domain.com
     DocumentRoot /path/to/ai-chatbot/public
     <Directory /path/to/ai-chatbot/public>
       AllowOverride All
       Require all granted
     </Directory>
   </VirtualHost>
   ```

5. **Restart Apache:**
   ```
   sudo systemctl restart apache2
   ```

6. **Visit your site in the browser and chat!**

---

### Security
- **Never commit your `.env`!**  
- Sessions are used for simple per-user threading.
- Only use on trusted servers.  
- For production, run behind HTTPS.

---

## Enable File Search in Your Assistant
- Ensure your OpenAI Assistant is configured with file search and files uploaded in the OpenAI console.

---

## Troubleshooting
- PHP 7.4+ and Composer required.
- Make sure `vendor/` exists (`composer install`).
- Permissions: The app writes temp files for threads.

---

**Questions?** Open an issue or contact the author.
