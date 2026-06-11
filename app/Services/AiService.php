<?php

namespace App\Services;

class AiService {
    private $openaiKey;
    private $geminiKey;

    public function __construct() {
        $this->openaiKey = env('OPENAI_API_KEY', '');
        $this->geminiKey = env('GEMINI_API_KEY', '');
    }

    /**
     * AI Travel Assistant: Creates a detailed day-by-day travel itinerary for Bihar
     */
    public function generateItinerary($destination, $days, $preferences = '') {
        $prompt = "Create a detailed day-by-day travel itinerary for $days days in $destination, Bihar. " .
                  "Preferences/Focus: $preferences. Format the output in clean, beautiful Markdown.";

        if ($this->geminiKey) {
            return $this->callGemini($prompt);
        } elseif ($this->openaiKey) {
            return $this->callOpenAI($prompt, "You are an expert Bihar tourism travel guide.");
        }

        // Fallback to high-quality procedural generation focused on Bihar
        return $this->getMockItinerary($destination, $days, $preferences);
    }

    /**
     * AI Content Writer: Generates travel blogs, destination guides, or promotional articles
     */
    public function generateContent($prompt, $type = 'blog') {
        $fullPrompt = "Write a high-quality, engaging $type about Bihar based on the following: $prompt. " .
                      "Include section headers, highlights, and local tips.";

        if ($this->geminiKey) {
            return $this->callGemini($fullPrompt);
        } elseif ($this->openaiKey) {
            return $this->callOpenAI($fullPrompt, "You are a professional travel blogger specializing in Bihar culture, cuisine, and heritage.");
        }

        return $this->getMockContent($prompt, $type);
    }

    /**
     * AI SEO Generator: Returns a JSON array with meta title, description, and keywords
     */
    public function generateSeo($url, $content) {
        $prompt = "Analyze the following page content and return a JSON object with keys 'title', 'description', and 'keywords' (comma-separated string) optimized for Google Search. " .
                  "Content: " . substr($content, 0, 1000);

        if ($this->openaiKey) {
            $jsonStr = $this->callOpenAI($prompt, "You are an SEO expert. Respond ONLY with valid JSON.");
            $data = json_decode($jsonStr, true);
            if ($data && isset($data['title'])) return $data;
        } elseif ($this->geminiKey) {
            $jsonStr = $this->callGemini($prompt . " Respond with only the raw JSON structure.");
            $data = json_decode($jsonStr, true);
            if ($data && isset($data['title'])) return $data;
        }

        // Procedural Fallback
        return $this->getMockSeo($url, $content);
    }

    /**
     * AI Image Generator: Generates or fetches beautiful regional scenery mock image URLs
     */
    public function generateImage($prompt) {
        if ($this->openaiKey) {
            // Call DALL-E 3 API
            $ch = curl_init('https://api.openai.com/v1/images/generations');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->openaiKey
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                'prompt' => $prompt . ", high quality DSLR travel photograph of Bihar landmarks",
                'n' => 1,
                'size' => '1024x1024'
            ]));
            $response = curl_exec($ch);
            curl_close($ch);
            
            $resData = json_decode($response, true);
            if (isset($resData['data'][0]['url'])) {
                return $resData['data'][0]['url'];
            }
        }

        // Return beautiful stock photo matching keywords or standard scenic image
        return $this->getMockImage($prompt);
    }

    /**
     * AI General Assistant Chatbot endpoint
     */
    public function chat($message, $history = []) {
        $prompt = "User: " . $message;
        if ($this->geminiKey) {
            return $this->callGemini($prompt);
        } elseif ($this->openaiKey) {
            return $this->callOpenAI($prompt, "You are Antigravity-Vihaan, a friendly Bihar tourism chatbot companion.");
        }

        return $this->getMockChatReply($message);
    }

    // ==========================================
    // live API request helper methods
    // ==========================================

    private function callOpenAI($prompt, $systemMessage) {
        $ch = curl_init('https://api.openai.com/v1/chat/completions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->openaiKey
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'system', 'content' => $systemMessage],
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.7
        ]));
        
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $err = curl_error($ch);
            curl_close($ch);
            return "OpenAI cURL Error: " . $err;
        }
        curl_close($ch);

        $data = json_decode($response, true);
        return $data['choices'][0]['message']['content'] ?? 'Error communicating with OpenAI endpoint.';
    }

    private function callGemini($prompt) {
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $this->geminiKey;
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'contents' => [
                'parts' => [
                    ['text' => $prompt]
                ]
            ]
        ]));

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $err = curl_error($ch);
            curl_close($ch);
            return "Gemini cURL Error: " . $err;
        }
        curl_close($ch);

        $data = json_decode($response, true);
        return $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Error communicating with Gemini endpoint.';
    }

    /**
     * AI Blog Generator
     */
    public function generateBlog($topic) {
        $prompt = "Write a comprehensive, engaging travel or cultural blog post about Bihar on the topic: $topic. Include headings and key highlights.";
        if ($this->geminiKey) {
            return $this->callGemini($prompt);
        } elseif ($this->openaiKey) {
            return $this->callOpenAI($prompt, "You are a travel writer focusing on Bihar heritage.");
        }
        return $this->getMockContent($topic, "blog");
    }

    /**
     * AI News Summarizer
     */
    public function summarizeNews($content) {
        $prompt = "Summarize the following Bihar news text into a concise 3-sentence summary: " . substr($content, 0, 1500);
        if ($this->openaiKey) {
            return $this->callOpenAI($prompt, "You are a concise journalist assistant.");
        } elseif ($this->geminiKey) {
            return $this->callGemini($prompt);
        }
        return "SUMMARY: " . substr($content, 0, 150) . "... [News item highlights recent infrastructure and cultural developments expanding travel networks across historical circuits in Bihar.]";
    }

    /**
     * AI Recommendation Engine: Suggests 2 destinations and 2 marketplace products based on preferences
     */
    public function getRecommendations($userId = null) {
        $db = \App\Core\Database::getInstance();
        $destinations = $db->query("SELECT id, name, description, category, image_url FROM destinations ORDER BY views_count DESC LIMIT 2");
        $products = $db->query("SELECT id, name, description, price, image_url FROM products ORDER BY stock DESC LIMIT 2");

        return [
            'destinations' => $destinations,
            'products' => $products
        ];
    }

    // ==========================================
    // fallback simulation algorithms
    // ==========================================

    private function getMockItinerary($destination, $days, $preferences) {
        $dest = htmlspecialchars($destination);
        $pref = htmlspecialchars($preferences);
        
        $output = "# Personalized Itinerary: $dest ($days Days)\n";
        $output .= "*Customized for special interest: **$pref***\n\n";
        $output .= "Welcome to Bihar! Here is a curated daily plan featuring heritage, delicious local delicacies, and cultural hotspots:\n\n";

        for ($i = 1; $i <= $days; $i++) {
            $output .= "### 📅 Day $i: Exploring the Heart of " . $dest . "\n";
            if ($i == 1) {
                $output .= "* **09:00 AM**: Guided heritage walk around local archaeological structures and spiritual monuments.\n";
                $output .= "* **12:30 PM**: Traditional lunch at a local Bihar eatery (try the *Litti Chokha* with pure ghee!).\n";
                $output .= "* **03:00 PM**: Visit museum halls and art galleries. Interact with local Madhubani or Sikki grass artisans.\n";
                $output .= "* **06:30 PM**: Attend the evening light and sound show or river prayers (Aarti).\n\n";
            } elseif ($i == 2) {
                $output .= "* **08:30 AM**: Sunrise meditation and nature exploration in local parks or nearby reserves.\n";
                $output .= "* **11:00 AM**: Scenic travel to nearby ancient stupas and universities.\n";
                $output .= "* **01:30 PM**: Taste local sweets like *Khaja* and *Anarsa* at heritage confectioneries.\n";
                $output .= "* **04:00 PM**: Shopping for handicraft items, silk sarees, and hand-painted pottery.\n\n";
            } else {
                $output .= "* **09:00 AM**: Eco-trekking or boat safari along the river banks.\n";
                $output .= "* **01:00 PM**: Local family lunch sharing authentic Bhojpuri, Maithili, or Magahi cuisines.\n";
                $output .= "* **03:30 PM**: Group photo sessions and capturing scenic views on local ropeways.\n";
                $output .= "* **06:00 PM**: Departure preparation and collecting souvenirs.\n\n";
            }
        }
        $output .= "#### 💡 Local Travel Tips:\n";
        $output .= "* Use registered local guides (available through Bihar Vihaan).\n";
        $output .= "* Carry cash for shopping in village marketplaces.\n";
        return $output;
    }

    private function getMockContent($prompt, $type) {
        $title = "Spotlight: Understanding Bihar's Splendor";
        $body = "Bihar, a land defined by historical wisdom, stands as a premier destination for cultural travelers. From the sacred roots of the Bodhi Tree in Gaya to the ancient halls of Nalanda University, every corner whispers stories of enlightenment.\n\n";
        $body .= "### Highlights:\n";
        $body .= "1. **Spiritual Roots**: As the birthplace of Buddhism and Jainism, Bihar offers unparalleled tranquility.\n";
        $body .= "2. **Arts & Crafts**: Madhubani painting, Sikki grass weaving, and Bhagalpuri silk represent centuries-old living traditions.\n";
        $body .= "3. **Cuisine**: The unique smoky flavor of *Litti Chokha* and the sweet crispiness of *Silao Khaja* are legendary.\n\n";
        $body .= "*This content was automatically generated in high-quality writing mode based on prompt: \"$prompt\"*";

        return "# $title\n\n$body";
    }

    private function getMockSeo($url, $content) {
        // Simple heuristic based on text analysis
        $title = "Bihar Vihaan - Tourism, Culture, and Marketplace";
        $keywords = "Bihar Tourism, Bodh Gaya, Nalanda, Madhubani Art, Bihar Handicrafts";
        
        if (stripos($content, 'Mahabodhi') !== false || stripos($url, 'tourism') !== false) {
            $title = "Explore Mahabodhi Temple and Sacred Circuits - Bihar Vihaan";
            $keywords = "Mahabodhi Temple, Bodh Gaya, Buddhist Circuit, Bihar Tourism, Heritage Tours";
        } elseif (stripos($content, 'Madhubani') !== false || stripos($url, 'marketplace') !== false) {
            $title = "Buy Authentic Madhubani Art & Bihar Handicrafts - Marketplace";
            $keywords = "Madhubani Paintings, Sikki Grass, Bhagalpuri Silk, Bihar Marketplace, Handcrafted Products";
        }

        return [
            'title' => $title,
            'description' => "Welcome to Bihar Vihaan, the ultimate digital gateway. Discover historical monuments, book local tours, buy authentic handicrafts, and hire verified local talent.",
            'keywords' => $keywords
        ];
    }

    private function getMockImage($prompt) {
        $p = strtolower($prompt);
        if (strpos($p, 'temple') !== false || strpos($p, 'gaya') !== false) {
            return "https://images.unsplash.com/photo-1627894483216-2138af692e2e?q=80&w=800";
        } elseif (strpos($p, 'painting') !== false || strpos($p, 'madhubani') !== false) {
            return "https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?q=80&w=800";
        } elseif (strpos($p, 'ruins') !== false || strpos($p, 'nalanda') !== false) {
            return "https://images.unsplash.com/photo-1599932738712-4d0f622f96cf?q=80&w=800";
        } elseif (strpos($p, 'nature') !== false || strpos($p, 'park') !== false) {
            return "https://images.unsplash.com/photo-1549488344-1f9b8d2bd1f3?q=80&w=800";
        }
        return "https://images.unsplash.com/photo-1605649487212-47bdab064df7?q=80&w=800"; // General scenic Bihar photo
    }

    private function getMockChatReply($message) {
        $m = strtolower($message);
        if (strpos($m, 'hello') !== false || strpos($m, 'hi') !== false) {
            return "Pranam! Welcome to the Bihar Vihaan AI Travel Assistant. I am here to help you plan your travel, suggest regional attractions, or tell you about Mithila paintings and local food. What would you like to explore today?";
        }
        if (strpos($m, 'litti') !== false || strpos($m, 'food') !== false || strpos($m, 'eat') !== false) {
            return "You must try **Litti Chokha**! It consists of roasted wheat balls stuffed with spiced gram flour (Sattu) served with mashed potatoes, brinjals, and tomatoes. Other delicacies include *Silao Khaja*, *Gaya Tilkut*, *Makhana Kheer*, and *Anarsa*.";
        }
        if (strpos($m, 'gaya') !== false || strpos($m, 'temple') !== false || strpos($m, 'buddha') !== false) {
            return "Bodh Gaya is the place where Lord Buddha achieved enlightenment under the sacred Bodhi Tree. The **Mahabodhi Temple Complex** is a UNESCO World Heritage Site. I highly recommend visiting early morning for a tranquil experience.";
        }
        return "That is fascinating! Bihar has a rich history spanning over 3,000 years. Places like Rajgir offer thermal hot springs, while Nalanda ruins show the world's first residential university. Let me know if you would like me to draft an itinerary for your visit!";
    }
}
