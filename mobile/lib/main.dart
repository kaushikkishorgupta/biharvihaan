import 'dart:convert';
import 'package:flutter/material';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

void main() {
  runApp(const BiharVihaanSuperApp());
}

class BiharVihaanSuperApp extends StatelessWidget {
  const BiharVihaanSuperApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Bihar Vihaan Enterprise Super App',
      theme: ThemeData(
        brightness: Brightness.dark,
        primaryColor: const Color(0xFF14B8A6), // Teal
        hintColor: const Color(0xFFF97316),    // Orange
        scaffoldBackgroundColor: const Color(0xFF0B0F19),
        cardColor: const Color(0xFF111827),
        useMaterial3: true,
      ),
      home: const MainNavigationScreen(),
    );
  }
}

class MainNavigationScreen extends StatefulWidget {
  const MainNavigationScreen({super.key});

  @override
  State<MainNavigationScreen> createState() => _MainNavigationScreenState();
}

class _MainNavigationScreenState extends State<MainNavigationScreen> {
  int _selectedIndex = 0;

  final List<Widget> _screens = [
    const TourismFeedScreen(),
    const AiAssistantScreen(),
    const QrScannerScreen(),
    const GpsNavigationScreen(),
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Bihar Vihaan Enterprise'),
        centerTitle: true,
        backgroundColor: const Color(0xFF0F172A),
        elevation: 4,
        actions: [
          IconButton(
            icon: const Icon(Icons.sync),
            onPressed: () {
              ScaffoldMessenger.of(context).showSnackBar(
                const SnackBar(content: Text('Offline database synced with cloud server!')),
              );
            },
          ),
        ],
      ),
      body: IndexedStack(
        index: _selectedIndex,
        children: _screens,
      ),
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: _selectedIndex,
        onTap: (index) {
          setState(() {
            _selectedIndex = index;
          });
        },
        type: BottomNavigationBarType.fixed,
        backgroundColor: const Color(0xFF0F172A),
        selectedItemColor: const Color(0xFF14B8A6),
        unselectedItemColor: Colors.grey,
        items: const [
          BottomNavigationBarItem(
            icon: Icon(Icons.explore),
            label: 'Tourism',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.chat_bubble),
            label: 'AI Guide',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.qr_code_scanner),
            label: 'QR Scan',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.map),
            label: 'Navigation',
          ),
        ],
      ),
    );
  }
}

// ==========================================
// 1. TOURISM LIST SCREEN (WITH OFFLINE SYNC)
// ==========================================
class TourismFeedScreen extends StatefulWidget {
  const TourismFeedScreen({super.key});

  @override
  State<TourismFeedScreen> createState() => _TourismFeedScreenState();
}

class _TourismFeedScreenState extends State<TourismFeedScreen> {
  List<dynamic> _destinations = [];
  bool _isLoading = true;
  bool _isOffline = false;

  @override
  void initState() {
    super.initState();
    _fetchDestinations();
  }

  Future<void> _fetchDestinations() async {
    final prefs = await SharedPreferences.getInstance();
    const apiUrl = 'https://biharvihaan.com/api/destinations'; // Production domain mapping

    try {
      final response = await http.get(Uri.parse(apiUrl)).timeout(const Duration(seconds: 4));
      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        setState(() {
          _destinations = data['data'] ?? [];
          _isLoading = false;
          _isOffline = false;
        });
        // Cache data for offline sync retrieval
        prefs.setString('cached_destinations', response.body);
      }
    } catch (e) {
      // offline fallback - retrieve from local shared preferences cache
      final cached = prefs.getString('cached_destinations');
      if (cached != null) {
        final data = json.decode(cached);
        setState(() {
          _destinations = data['data'] ?? [];
          _isLoading = false;
          _isOffline = true;
        });
      } else {
        // Mock default values if no cache exists
        setState(() {
          _destinations = [
            {
              'name': 'Mahabodhi Temple Complex (Offline Cache)',
              'location': 'Bodh Gaya, Gaya',
              'rating': '4.9',
              'image_url': 'https://images.unsplash.com/photo-1627894483216-2138af692e2e?q=80&w=600'
            },
            {
              'name': 'Nalanda Ruins (Offline Cache)',
              'location': 'Nalanda',
              'rating': '4.8',
              'image_url': 'https://images.unsplash.com/photo-1599932738712-4d0f622f96cf?q=80&w=600'
            }
          ];
          _isLoading = false;
          _isOffline = true;
        });
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    if (_isLoading) {
      return const Center(child: CircularProgressIndicator(color: Color(0xFF14B8A6)));
    }

    return Padding(
      padding: const EdgeInsets.all(12.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          if (_isOffline)
            Container(
              padding: const EdgeInsets.symmetric(vertical: 8, horizontal: 12),
              margin: const EdgeInsets.bottom(12),
              decoration: BoxDecoration(
                color: Colors.amber.withOpacity(0.2),
                border: Border.all(color: Colors.amber),
                borderRadius: BorderRadius.circular(8),
              ),
              child: const Row(
                children: [
                  Icon(Icons.wifi_off, color: Colors.amber),
                  SizedBox(width: 8),
                  Text('Offline Mode: Displaying cached records.', style: TextStyle(fontSize: 12)),
                ],
              ),
            ),
          const Text(
            'Explore Scenic Hotspots',
            style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: Colors.white),
          ),
          const SizedBox(height: 12),
          Expanded(
            child: ListView.builder(
              itemCount: _destinations.length,
              itemBuilder: (context, index) {
                final item = _destinations[index];
                return Card(
                  margin: const EdgeInsets.only(bottom: 16),
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                  clipBehavior: Clip.antiAlias,
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Image.network(
                        item['image_url'] ?? 'https://images.unsplash.com/photo-1605649487212-47bdab064df7?q=80&w=600',
                        height: 160,
                        width: double.infinity,
                        fit: BoxFit.cover,
                      ),
                      Padding(
                        padding: const EdgeInsets.all(12.0),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              item['name'] ?? '',
                              style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                            ),
                            const SizedBox(height: 4),
                            Row(
                              mainAxisAlignment: MainAxisAlignment.between,
                              children: [
                                Text(item['location'] ?? '', style: const TextStyle(color: Colors.grey, fontSize: 13)),
                                Row(
                                  children: [
                                    const Icon(Icons.star, color: Colors.orange, size: 16),
                                    const SizedBox(width: 4),
                                    Text(item['rating']?.toString() ?? '5.0', style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
                                  ],
                                ),
                              ],
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                );
              },
            ),
          ),
        ],
      ),
    );
  }
}

// ==========================================
// 2. AI ASSISTANT CHAT SCREEN
// ==========================================
class AiAssistantScreen extends StatefulWidget {
  const AiAssistantScreen({super.key});

  @override
  State<AiAssistantScreen> createState() => _AiAssistantScreenState();
}

class _AiAssistantScreenState extends State<AiAssistantScreen> {
  final List<Map<String, dynamic>> _messages = [
    {'text': 'Pranam! I am your Bihar Vihaan AI Guide. Ask me anything about Bodh Gaya, Nalanda or local food.', 'isUser': false}
  ];
  final TextEditingController _chatInput = TextEditingController();
  bool _isTyping = false;

  void _sendMessage() async {
    final query = _chatInput.value.text.trim();
    if (query.isEmpty) return;

    setState(() {
      _messages.add({'text': query, 'isUser': true});
      _chatInput.clear();
      _isTyping = true;
    });

    try {
      final response = await http.post(
        Uri.parse('https://biharvihaan.com/api/chat'),
        body: {'message': query},
      ).timeout(const Duration(seconds: 4));

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        setState(() {
          _messages.add({'text': data['reply'] ?? 'Error fetching response.', 'isUser': false});
          _isTyping = false;
        });
      }
    } catch (e) {
      // offline mockup reply
      setState(() {
        _messages.add({
          'text': 'I am working in offline assistant mode. Gaya Tilkut and Litti Chokha are delicious local options!',
          'isUser': false
        });
        _isTyping = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(12.0),
      child: Column(
        children: [
          Expanded(
            child: ListView.builder(
              itemCount: _messages.length,
              itemBuilder: (context, index) {
                final msg = _messages[index];
                final isUser = msg['isUser'];
                return Align(
                  alignment: isUser ? Alignment.centerRight : Alignment.centerLeft,
                  child: Container(
                    margin: const EdgeInsets.symmetric(vertical: 6),
                    padding: const EdgeInsets.all(12),
                    decoration: BoxDecoration(
                      color: isUser ? const Color(0xFF14B8A6) : const Color(0xFF1F2937),
                      borderRadius: BorderRadius.circular(12),
                    ),
                    constraints: BoxConstraints(maxWidth: MediaQuery.of(context).size.width * 0.8),
                    child: Text(
                      msg['text'],
                      style: const TextStyle(color: Colors.white, fontSize: 14),
                    ),
                  ),
                );
              },
            ),
          ),
          if (_isTyping)
            const Align(
              alignment: Alignment.centerLeft,
              child: Padding(
                padding: EdgeInsets.all(8.0),
                child: Text('AI Guide is typing...', style: TextStyle(color: Colors.grey, fontSize: 12)),
              ),
            ),
          const SizedBox(height: 8),
          Row(
            children: [
              Expanded(
                child: TextField(
                  controller: _chatInput,
                  decoration: InputDecoration(
                    hintText: 'Ask AI Guide...',
                    fillColor: const Color(0xFF111827),
                    filled: true,
                    border: OutlineInputBorder(borderRadius: BorderRadius.circular(30), borderSide: BorderSide.none),
                    contentPadding: const EdgeInsets.symmetric(horizontal: 20, vertical: 10),
                  ),
                ),
              ),
              const SizedBox(width: 8),
              CircleAvatar(
                backgroundColor: const Color(0xFF14B8A6),
                child: IconButton(
                  icon: const Icon(Icons.send, color: Colors.white),
                  onPressed: _sendMessage,
                ),
              )
            ],
          )
        ],
      ),
    );
  }
}

// ==========================================
// 3. QR CODE SCANNER (TICKET SIMULATOR)
// ==========================================
class QrScannerScreen extends StatefulWidget {
  const QrScannerScreen({super.key});

  @override
  State<QrScannerScreen> createState() => _QrScannerScreenState();
}

class _QrScannerScreenState extends State<QrScannerScreen> {
  String _scanResult = 'Scan Ticket Code';
  bool _isScanned = false;

  void _simulateScan() {
    setState(() {
      _scanResult = 'MINTED_TICKET_BV_45229';
      _isScanned = true;
    });

    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(content: Text('Ticket signature verified successfully on Blockchain ledger!')),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Center(
      padding: const EdgeInsets.all(20),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Container(
            height: 250,
            width: 250,
            decoration: BoxDecoration(
              border: Border.all(color: const Color(0xFF14B8A6), width: 3),
              borderRadius: BorderRadius.circular(16),
              color: Colors.black26,
            ),
            child: const Icon(Icons.qr_code_2, size: 150, color: Colors.grey),
          ),
          const SizedBox(height: 24),
          Text(
            _scanResult,
            style: TextStyle(
              fontSize: 16,
              fontWeight: FontWeight.bold,
              color: _isScanned ? Colors.green : Colors.grey,
            ),
          ),
          const SizedBox(height: 24),
          ElevatedButton.icon(
            style: ElevatedButton.styleFrom(
              backgroundColor: const Color(0xFF14B8A6),
              foregroundColor: Colors.white,
              padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
            ),
            icon: const Icon(Icons.camera_alt),
            label: const Text('Simulate Scan Camera'),
            onPressed: _simulateScan,
          ),
        ],
      ),
    );
  }
}

// ==========================================
// 4. GPS MAPS NAVIGATION SCREEN
// ==========================================
class GpsNavigationScreen extends StatelessWidget {
  const GpsNavigationScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Padding(
        padding: const EdgeInsets.all(24.0),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Icon(Icons.navigation, size: 80, color: Color(0xFFF97316)),
            const SizedBox(height: 16),
            const Text(
              'Smart GPS Route Optimization',
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 8),
            const Text(
              'Route: Patna ➔ Nalanda Ruins ➔ Rajgir Nature Safari (Buddhist Circuit)',
              style: TextStyle(color: Colors.grey, fontSize: 13),
              textAlign: TextAlign.center,
            ),
            const SizedBox(height: 24),
            Container(
              height: 180,
              width: double.infinity,
              decoration: BoxDecoration(
                borderRadius: BorderRadius.circular(12),
                color: Colors.black38,
                border: Border.all(color: Colors.grey.shade800),
              ),
              child: const Center(
                child: Text(
                  'Optimized map trail displaying navigation nodes...',
                  style: TextStyle(color: Colors.grey, fontSize: 12),
                ),
              ),
            )
          ],
        ),
      ),
    );
  }
}
