<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.pesan-guru.index') }}" class="p-2 rounded-lg hover:bg-surface-100 text-surface-400 hover:text-surface-600 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h2 class="font-extrabold text-lg md:text-2xl text-surface-900 tracking-tight">Chat Pesan Guru</h2>
        </div>
    </x-slot>

    <div class="py-4 md:py-6 animate-fade-in"
         x-data="adminChatRoom({
             activeId: {{ $pesan->id }},
             conversations: @js($conversations),
             authId: {{ auth()->id() }}
         })">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col lg:flex-row h-[calc(100vh-14rem)] border border-surface-200 dark:border-zinc-800 rounded-2xl overflow-hidden bg-white dark:bg-zinc-950 shadow-glass animate-fade-in">
                
                {{-- Sidebar Kiri - Daftar Percakapan --}}
                <div :class="activePanel === 'list' ? 'flex w-full h-full' : 'hidden lg:flex lg:w-72 h-full'"
                     class="border-b lg:border-b-0 lg:border-r border-surface-200 dark:border-zinc-800 flex-col bg-surface-50/50 dark:bg-zinc-900/30 overflow-y-auto shrink-0">
                    <div class="p-4 border-b border-surface-200 dark:border-zinc-800">
                        <h3 class="text-xs font-bold text-surface-400 uppercase tracking-wider">Daftar Chat Guru</h3>
                    </div>
                    <div class="flex-1 divide-y divide-surface-100 dark:divide-zinc-800/50">
                        <template x-for="c in conversations" :key="c.id">
                            <a href="#" 
                               @click.prevent="selectConversation(c)"
                               :class="activeId === c.id ? 'bg-indigo-50/40 dark:bg-indigo-950/15 border-l-4 border-l-brand-600' : ''"
                               class="flex items-center gap-3 p-4 hover:bg-surface-100/50 dark:hover:bg-zinc-900/50 transition-colors">
                                <div class="w-10 h-10 rounded-full bg-brand-100 dark:bg-brand-950/40 text-brand-600 dark:text-brand-400 flex items-center justify-center font-bold shrink-0"
                                     x-text="c.kategori.charAt(0).toUpperCase()">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-xs font-bold text-surface-900 dark:text-zinc-100 truncate pr-2"
                                            x-text="c.guru?.user?.name || 'Guru'">
                                        </h4>
                                        <span class="text-[9px] text-surface-400 font-medium shrink-0"
                                              x-text="new Date(c.created_at).toLocaleDateString('id-ID', {day: '2-digit', month: '2-digit'})"></span>
                                    </div>
                                    <p class="text-xs text-surface-500 dark:text-zinc-400 truncate mt-0.5" x-text="c.pesan"></p>
                                    
                                    <div class="flex items-center justify-between mt-1.5">
                                        <span class="text-[9px] font-semibold px-2 py-0.5 rounded bg-surface-100 dark:bg-zinc-850 text-surface-600 dark:text-zinc-300"
                                              x-text="c.kategori.charAt(0).toUpperCase() + c.kategori.slice(1)">
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </template>
                    </div>
                </div>

                {{-- Chat Area Utama --}}
                <div :class="activePanel === 'chat' ? 'flex flex-1 w-full h-full' : 'hidden lg:flex lg:flex-1 h-full'"
                     class="flex-col bg-surface-50 dark:bg-zinc-950 relative overflow-hidden">
                    
                    {{-- Header Chat --}}
                    <div class="px-4 sm:px-6 py-4 bg-white dark:bg-zinc-900 border-b border-surface-200 dark:border-zinc-800 flex items-center justify-between shrink-0 shadow-sm">
                        <div class="flex items-center gap-2 sm:gap-3">
                            <button type="button" @click="activePanel = 'list'" class="lg:hidden p-1.5 -ml-1 rounded-lg hover:bg-surface-100 dark:hover:bg-zinc-800 text-surface-500 hover:text-surface-700 transition-colors shrink-0">
                                <i data-lucide="chevron-left" class="w-6 h-6"></i>
                            </button>
                            <div class="w-10 h-10 rounded-full bg-brand-600 text-white flex items-center justify-center font-extrabold text-sm shrink-0"
                                 x-text="(activeConversation.guru?.user?.name || 'G').charAt(0).toUpperCase()">
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-surface-900 dark:text-zinc-100"
                                    x-text="activeConversation.guru?.user?.name || 'Guru'">
                                </h3>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span class="text-[10px] text-emerald-600 font-semibold">Real-Time Polling</span>
                                    <span class="text-surface-300 dark:text-zinc-700">•</span>
                                    <span class="text-[10px] text-surface-500 dark:text-zinc-400 font-medium truncate max-w-[120px] sm:max-w-none" x-text="'Topik: ' + activeConversation.subjek"></span>
                                </div>
                            </div>
                        </div>
                        <button type="button" @click="activePanel = 'profile'" class="lg:hidden p-2 rounded-lg hover:bg-surface-100 dark:hover:bg-zinc-800 text-surface-500 hover:text-surface-700 transition-colors">
                            <i data-lucide="info" class="w-5 h-5"></i>
                        </button>
                    </div>

                    {{-- Balon Chat History Container --}}
                    <div x-ref="chatContainer" class="flex-1 overflow-y-auto p-6 space-y-4 bg-dots dark:bg-zinc-950 max-h-[calc(100vh-27rem)] md:max-h-[calc(100vh-25rem)]">
                        <template x-for="msg in messages" :key="msg.id + '-' + msg.created_at">
                            <div class="flex w-full" :class="msg.is_me ? 'justify-end' : 'justify-start'">
                                <div class="flex items-end gap-2 max-w-[75%] md:max-w-[65%]">
                                    <template x-if="!msg.is_me">
                                        <div class="w-8 h-8 rounded-full bg-brand-100 text-brand-700 dark:bg-brand-950 dark:text-brand-400 flex items-center justify-center text-xs font-bold shrink-0">
                                            <span x-text="msg.sender_name.charAt(0).toUpperCase()"></span>
                                        </div>
                                    </template>
                                    
                                    <div class="flex flex-col">
                                        <template x-if="!msg.is_me">
                                            <span class="text-[10px] font-semibold text-surface-500 dark:text-zinc-400 mb-0.5 ml-1" x-text="msg.sender_name"></span>
                                        </template>
                                        
                                        <div class="p-3.5 rounded-2xl shadow-sm relative group"
                                             :class="msg.is_me 
                                                ? 'bg-brand-600 text-white rounded-br-none' 
                                                : 'bg-white dark:bg-zinc-900 border border-surface-200 dark:border-zinc-800/80 text-surface-900 dark:text-zinc-100 rounded-bl-none'">
                                            
                                            <p class="text-xs md:text-sm whitespace-pre-line leading-relaxed" x-text="msg.pesan"></p>
                                            
                                            <template x-if="msg.lampiran">
                                                <div class="mt-3 border-t border-white/10 dark:border-zinc-800/50 pt-2 flex flex-col gap-2">
                                                    <template x-if="msg.is_image">
                                                        <div class="max-w-xs rounded-lg overflow-hidden border border-white/20 dark:border-zinc-800">
                                                            <img :src="msg.lampiran" class="max-h-40 object-contain" alt="Lampiran">
                                                        </div>
                                                    </template>
                                                    <a :href="msg.lampiran" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[11px] font-bold transition-all shadow-sm"
                                                       :class="msg.is_me 
                                                          ? 'bg-white/10 hover:bg-white/20 text-white' 
                                                          : 'bg-indigo-50 hover:bg-indigo-100 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400'">
                                                        <i data-lucide="download" class="w-3 h-3 shrink-0"></i>
                                                        <span class="truncate" x-text="msg.lampiran_name"></span>
                                                    </a>
                                                </div>
                                            </template>
                                            
                                            <div class="flex items-center justify-end gap-1 mt-1 text-[9px] font-medium"
                                                 :class="msg.is_me ? 'text-white/65' : 'text-surface-400 dark:text-zinc-500'">
                                                <span x-text="msg.time"></span>
                                                <template x-if="msg.is_me">
                                                    <i data-lucide="check-check" class="w-3 h-3 shrink-0"></i>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                        
                        <template x-if="messages.length === 0">
                            <div class="flex flex-col items-center justify-center py-16">
                                <div class="w-10 h-10 rounded-full bg-brand-50 text-brand-600 dark:bg-brand-950/30 flex items-center justify-center mb-3">
                                    <i data-lucide="loader" class="w-5 h-5 animate-spin"></i>
                                </div>
                                <p class="text-xs text-surface-400 dark:text-zinc-500">Memuat riwayat chat...</p>
                            </div>
                        </template>
                    </div>

                    {{-- Form Input Chat --}}
                    <div class="p-4 bg-white dark:bg-zinc-900 border-t border-surface-200 dark:border-zinc-800 shrink-0">
                        
                        {{-- Lampiran Preview --}}
                        <template x-if="attachmentPreview">
                            <div class="mb-3 p-2 bg-surface-50 dark:bg-zinc-800/40 rounded-xl flex items-center justify-between border border-surface-200 dark:border-zinc-700/60 animate-slide-up">
                                <div class="flex items-center gap-2.5">
                                    <template x-if="isImageAttachment">
                                        <img :src="attachmentPreview" class="w-10 h-10 object-cover rounded-lg border dark:border-zinc-750">
                                    </template>
                                    <template x-if="!isImageAttachment">
                                        <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400 flex items-center justify-center">
                                            <i data-lucide="file" class="w-5 h-5"></i>
                                        </div>
                                    </template>
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-surface-900 dark:text-zinc-100 truncate max-w-[200px] sm:max-w-xs" x-text="attachmentName"></p>
                                        <p class="text-[10px] text-surface-400 font-medium" x-text="attachmentSize"></p>
                                    </div>
                                </div>
                                <button type="button" @click="removeAttachment()" class="p-1 rounded-full hover:bg-surface-200 dark:hover:bg-zinc-700 text-surface-400 hover:text-surface-600 transition-colors">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </template>

                        <form @submit.prevent="sendMessage()" enctype="multipart/form-data">
                            <div class="flex items-center gap-3">
                                
                                <button type="button" @click="$refs.fileInput.click()" 
                                        class="p-3 rounded-xl bg-surface-50 hover:bg-surface-100 dark:bg-zinc-800 dark:hover:bg-zinc-750 text-surface-500 hover:text-surface-700 dark:text-zinc-400 dark:hover:text-zinc-200 transition-colors shadow-sm border border-surface-200 dark:border-zinc-700/60 shrink-0">
                                    <i data-lucide="paperclip" class="w-5 h-5"></i>
                                </button>
                                <input x-ref="fileInput" type="file" class="hidden" @change="handleFileChange($event)">

                                <div class="flex-1">
                                    <textarea x-model="newMessage" rows="1" @keydown.enter.prevent="sendMessage()"
                                              class="form-input-premium w-full resize-none py-3 rounded-xl min-h-[44px] max-h-24 text-sm scrollbar-thin focus:ring-brand-500/20"
                                              placeholder="Tulis balasan pesan di sini..."></textarea>
                                </div>

                                <button type="submit" :disabled="!newMessage.trim() && !attachment"
                                        class="p-3 rounded-xl text-white bg-brand-600 hover:bg-brand-700 disabled:opacity-40 disabled:hover:bg-brand-600 transition-all shadow-md shrink-0">
                                    <i data-lucide="send" class="w-5 h-5"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                </div>

                {{-- Sidebar Kanan - Profil Guru --}}
                <div :class="activePanel === 'profile' ? 'flex w-full h-full' : 'hidden lg:flex lg:w-72 h-full'" 
                     class="border-t lg:border-t-0 lg:border-l border-surface-200 dark:border-zinc-800 flex-col bg-surface-50/50 dark:bg-zinc-900/30 p-6 overflow-y-auto shrink-0 space-y-6">

                    {{-- Back button on mobile --}}
                    <button type="button" @click="activePanel = 'chat'" class="lg:hidden flex items-center gap-1.5 text-xs font-bold text-brand-600 hover:text-brand-700 transition-colors mb-2">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Chat
                    </button>

                    {{-- Profil Guru --}}
                    <div class="card-premium p-4 shadow-sm border border-surface-200 dark:border-zinc-800/80 bg-white dark:bg-zinc-900">
                        <h4 class="text-xs font-bold text-surface-400 dark:text-zinc-500 uppercase tracking-wider mb-4">Profil Guru</h4>
                        
                        <div class="space-y-4">
                            <div>
                                <span class="text-[10px] text-surface-400 dark:text-zinc-500 font-bold uppercase tracking-wider">Nama Guru</span>
                                <p class="text-xs font-bold text-surface-900 dark:text-zinc-100 mt-0.5" x-text="activeConversation.guru?.user?.name || '-'"></p>
                            </div>
                            <div>
                                <span class="text-[10px] text-surface-400 dark:text-zinc-500 font-bold uppercase tracking-wider">NIP</span>
                                <p class="text-xs font-bold text-surface-900 dark:text-zinc-100 mt-0.5" x-text="activeConversation.guru?.nip || '-'"></p>
                            </div>
                            <div>
                                <span class="text-[10px] text-surface-400 dark:text-zinc-500 font-bold uppercase tracking-wider">Kontak HP</span>
                                <p class="text-xs text-surface-700 dark:text-zinc-350 mt-0.5" x-text="activeConversation.guru?.no_hp || '-'"></p>
                            </div>
                            <div>
                                <span class="text-[10px] text-surface-400 dark:text-zinc-500 font-bold uppercase tracking-wider">Email</span>
                                <p class="text-xs text-surface-700 dark:text-zinc-350 mt-0.5 truncate" x-text="activeConversation.guru?.user?.email || '-'"></p>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        function adminChatRoom(config) {
            return {
                activeId: config.activeId,
                conversations: config.conversations,
                authId: config.authId,
                activePanel: 'chat',
                messages: [],
                newMessage: '',
                attachment: null,
                attachmentName: '',
                attachmentSize: '',
                attachmentPreview: null,
                isImageAttachment: false,
                pollingInterval: null,

                get activeConversation() {
                    return this.conversations.find(c => c.id === this.activeId) || {};
                },

                get fetchUrl() {
                    return `/admin/pesan-guru/${this.activeId}/messages`;
                },

                get sendUrl() {
                    return `/admin/pesan-guru/${this.activeId}/balas`;
                },

                init() {
                    this.fetchMessages();
                    
                    // Poll for new messages every 3 seconds
                    this.pollingInterval = setInterval(() => {
                        this.fetchMessages();
                    }, 3000);

                    // Re-render lucide icons when window updates
                    this.$watch('messages', () => {
                        this.$nextTick(() => {
                            if (window.lucide) {
                                window.lucide.createIcons();
                            }
                        });
                    });

                    // Handle history navigation (back/forward buttons)
                    window.addEventListener('popstate', (event) => {
                        if (event.state && event.state.id) {
                            this.activeId = event.state.id;
                            this.fetchMessages();
                            this.activePanel = 'chat';
                        }
                    });
                },

                selectConversation(c) {
                    this.activeId = c.id;
                    this.newMessage = '';
                    this.removeAttachment();
                    this.messages = [];
                    this.fetchMessages();
                    this.activePanel = 'chat';
                    
                    const newUrl = `/admin/pesan-guru/${c.id}`;
                    window.history.pushState({ id: c.id }, '', newUrl);
                },

                fetchMessages() {
                    axios.get(this.fetchUrl)
                        .then(response => {
                            const newMsgs = response.data.messages;
                            
                            // Scroll only if new messages length is different
                            if (newMsgs.length !== this.messages.length) {
                                this.messages = newMsgs;
                                this.$nextTick(() => {
                                    this.scrollToBottom();
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching messages:', error);
                        });
                },

                sendMessage() {
                    const content = this.newMessage.trim();
                    if (!content && !this.attachment) return;

                    const formData = new FormData();
                    formData.append('tanggapan', content);
                    formData.append('status', 'proses');
                    
                    if (this.attachment) {
                        formData.append('lampiran', this.attachment);
                    }

                    this.newMessage = '';
                    const file = this.attachment;
                    this.removeAttachment();

                    axios.post(this.sendUrl, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then(response => {
                        if (response.data.success) {
                            this.messages.push(response.data.message);
                            this.$nextTick(() => {
                                this.scrollToBottom();
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error sending message:', error);
                        this.newMessage = content;
                    });
                },

                handleFileChange(event) {
                    const file = event.target.files[0];
                    if (!file) return;

                    this.attachment = file;
                    this.attachmentName = file.name;
                    
                    const sizeKB = (file.size / 1024).toFixed(1);
                    this.attachmentSize = sizeKB + ' KB';

                    this.isImageAttachment = file.type.startsWith('image/');
                    
                    if (this.isImageAttachment) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.attachmentPreview = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    } else {
                        this.attachmentPreview = 'file';
                    }
                },

                removeAttachment() {
                    this.attachment = null;
                    this.attachmentName = '';
                    this.attachmentSize = '';
                    this.attachmentPreview = null;
                    this.isImageAttachment = false;
                    this.$refs.fileInput.value = '';
                },

                scrollToBottom() {
                    const container = this.$refs.chatContainer;
                    if (container) {
                        container.scrollTop = container.scrollHeight;
                    }
                },

                destroy() {
                    if (this.pollingInterval) {
                        clearInterval(this.pollingInterval);
                    }
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
