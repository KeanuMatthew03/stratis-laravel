@extends('layouts.app')

@section('content')
<div class="flex-1 flex h-full" x-data="chatApp()">
    
    <!-- Sidebar for Chat History -->
    <aside class="w-64 bg-white border-r border-slate-200 flex flex-col h-full shrink-0 shadow-sm z-10">
        <div class="p-4 border-b border-slate-200 bg-slate-50">
            <button @click="createNewChat" class="w-full flex items-center justify-center gap-2 bg-[#0F766E] border border-[#0F766E] hover:bg-[#115E59] text-white py-2.5 px-4  text-sm font-medium transition-colors shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                NEW CHAT
            </button>
        </div>
        
        <div class="flex-1 overflow-y-auto p-4 flex flex-col gap-2 scrollbar-hide">
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2 border-b border-slate-100 pb-2 px-1">Recent Conversations</div>
            <template x-for="session in sessions" :key="session.id">
                <button 
                    @click="loadChat(session.id)" 
                    class="w-full text-left px-3 py-2.5  text-sm truncate transition-colors relative group"
                    :class="currentSessionId === session.id ? 'bg-[#F0FDF4] text-[#14532D] font-medium shadow-sm ring-1 ring-inset ring-[#86EFAC]' : 'bg-transparent text-slate-600 hover:bg-slate-100'"
                >
                    <span x-text="session.title"></span>
                    <div 
                        @click.stop="deleteChat(session.id)" 
                        class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5  opacity-0 group-hover:opacity-100 transition-opacity bg-white/80 hover:bg-red-50 hover:text-red-600"
                        :class="currentSessionId === session.id ? 'text-[#14532D]' : 'text-slate-400'"
                        title="Delete chat"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                    </div>
                </button>
            </template>
            <template x-if="sessions.length === 0">
                <div class="text-sm italic text-slate-400 px-1">No history yet.</div>
            </template>
        </div>
    </aside>

    <!-- Main Chat Area -->
    <div class="flex-1 flex flex-col h-full relative bg-white">
        <!-- Header -->
        <header class="h-16 border-b border-slate-200 bg-white flex items-center px-8 shrink-0 z-10 sticky top-0">
            <h2 class="text-lg font-semibold text-slate-700" x-text="getCurrentTitle()"></h2>
        </header>

        <!-- Chat Messages Container -->
        <div class="flex-1 overflow-y-auto p-4 sm:p-8 scrollbar-hide flex flex-col" id="chat-container">
            <div class="max-w-4xl mx-auto w-full flex-1 flex flex-col">
                
                <!-- Welcome Screen -->
                <template x-if="getCurrentMessages().length === 0">
                    <div class="flex flex-col gap-10 my-auto">
                        <div class="text-center border-b border-slate-100 pb-8">
                            <h2 class="text-3xl font-bold mb-3 text-slate-800">AI Tourism Intelligence</h2>
                            <p class="text-slate-500 text-lg">How can I help you analyze the tourism data today?</p>
                        </div>
                        
                        <!-- Info Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-white p-6 border border-slate-200  flex flex-col gap-4 shadow-sm">
                                <div class="text-sm font-semibold uppercase tracking-wider text-[#0F766E] flex items-center gap-2 border-b border-slate-100 pb-3">
                                    About This AI
                                </div>
                                <div class="text-sm text-slate-600 leading-relaxed flex flex-col gap-3">
                                    <p>I am designed to help you discover insights from our specific <strong>Indonesian Tourism Dataset</strong>.</p>
                                    <p>I can analyze foreign visitor statistics, compare data between countries, and highlight key tourism trends for this region.</p>
                                </div>
                            </div>
                            
                            <div class="bg-white p-6 border border-slate-200  flex flex-col gap-4 shadow-sm">
                                <div class="text-sm font-semibold uppercase tracking-wider text-[#0F766E] flex items-center gap-2 border-b border-slate-100 pb-3">
                                    Suggestions
                                </div>
                                <div class="flex flex-col gap-2">
                                    <template x-for="q in suggestions" :key="q">
                                        <button @click="sendPrompt(q)" class="text-left text-sm text-slate-600 bg-slate-50 border border-slate-100 hover:border-[#0F766E] hover:bg-[#F0FDF4] hover:text-[#0F766E] px-4 py-3  transition-colors truncate" x-text="q"></button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Messages -->
                <template x-if="getCurrentMessages().length > 0">
                    <div class="flex flex-col gap-8 pb-10">
                        <template x-for="(msg, index) in getCurrentMessages()" :key="index">
                            <div class="flex flex-col" :class="msg.role === 'user' ? 'items-end' : 'items-start'">
                                <div class="flex items-center gap-2 mb-1.5 px-1" :class="msg.role === 'user' ? 'flex-row-reverse' : 'flex-row'">
                                    <div class="w-8 h-8  flex items-center justify-center text-xs font-bold shrink-0"
                                         :class="msg.role === 'user' ? 'bg-[#0F766E] text-white shadow-sm' : 'bg-slate-100 border border-slate-200 text-[#0F766E]'">
                                        <span x-text="msg.role === 'user' ? 'U' : 'AI'"></span>
                                    </div>
                                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide" x-text="msg.role === 'user' ? 'You' : 'Assistant'"></span>
                                </div>
                                <div class="px-6 py-4  max-w-[85%] text-sm shadow-sm"
                                     :class="msg.role === 'user' ? 'bg-[#0F766E] text-white ' : 'bg-slate-50 border border-slate-200 text-slate-800 '">
                                    <div class="prose prose-sm max-w-none" :class="msg.role === 'user' ? 'prose-invert' : 'prose-slate'" x-html="msg.role === 'ai' ? marked.parse(msg.text) : msg.text"></div>
                                </div>
                            </div>
                        </template>
                        
                        <!-- Loading Indicator -->
                        <template x-if="isLoading">
                            <div class="flex flex-col items-start">
                                <div class="flex items-center gap-2 mb-1.5 px-1">
                                    <div class="w-8 h-8  border border-slate-200 bg-slate-100 flex items-center justify-center text-xs font-bold text-[#0F766E] shrink-0">AI</div>
                                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Assistant</span>
                                </div>
                                <div class="px-6 py-5   border border-slate-200 bg-slate-50 text-slate-700 shadow-sm">
                                    <div class="flex gap-2">
                                        <div class="w-2 h-2  bg-[#0F766E] animate-bounce" style="animation-delay: 0s;"></div>
                                        <div class="w-2 h-2  bg-[#0F766E] animate-bounce" style="animation-delay: 0.2s;"></div>
                                        <div class="w-2 h-2  bg-[#0F766E] animate-bounce" style="animation-delay: 0.4s;"></div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-6 bg-white shrink-0 border-t border-slate-200">
            <div class="max-w-4xl mx-auto">
                <form @submit.prevent="submitForm" class="relative bg-slate-50 border border-slate-300  flex items-end p-2 gap-2 hover:border-[#0F766E] focus-within:border-[#0F766E] focus-within:ring-1 focus-within:ring-[#0F766E] transition-all shadow-sm">
                    <textarea
                        x-model="inputValue"
                        @keydown.enter.prevent="submitForm"
                        class="w-full text-slate-800 bg-transparent p-3 outline-none resize-none max-h-32 min-h-[48px] text-sm"
                        placeholder="Message AI Intelligence..."
                        rows="1"
                        x-ref="chatInput"
                    ></textarea>
                    
                    <button type="submit" :disabled="!inputValue.trim() || isLoading" class="p-3 m-1  bg-[#0F766E] text-white hover:bg-[#115E59] disabled:opacity-50 disabled:bg-slate-200 disabled:text-slate-400 transition-colors shrink-0 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('chatApp', () => ({
            suggestions: [
                "Which country has the highest visitors?",
                "Compare Malaysia and Singapore.",
                "Summarize March visitors.",
                "Growth between April and May."
            ],
            inputValue: '',
            isLoading: false,
            sessions: [],
            currentSessionId: null,

            init() {
                const saved = localStorage.getItem('tourism_chat_sessions');
                if (saved) {
                    try {
                        this.sessions = JSON.parse(saved);
                    } catch (e) {
                        this.sessions = [];
                    }
                }
                
                if (this.sessions.length === 0) {
                    this.createNewChat();
                } else {
                    this.currentSessionId = localStorage.getItem('tourism_current_session') || this.sessions[0].id;
                }

                this.$watch('inputValue', () => {
                    const el = this.$refs.chatInput;
                    if (el) {
                        el.style.height = 'auto';
                        el.style.height = Math.min(el.scrollHeight, 128) + 'px';
                    }
                });
            },

            save() {
                localStorage.setItem('tourism_chat_sessions', JSON.stringify(this.sessions));
                if (this.currentSessionId) {
                    localStorage.setItem('tourism_current_session', this.currentSessionId);
                }
            },

            createNewChat() {
                const id = Date.now().toString();
                this.sessions.unshift({
                    id: id,
                    title: 'New Chat',
                    messages: []
                });
                this.currentSessionId = id;
                this.save();
                this.focusInput();
            },

            loadChat(id) {
                this.currentSessionId = id;
                this.save();
                this.scrollToBottom();
                this.focusInput();
            },

            deleteChat(id) {
                this.sessions = this.sessions.filter(s => s.id !== id);
                if (this.sessions.length === 0) {
                    this.createNewChat();
                } else if (this.currentSessionId === id) {
                    this.currentSessionId = this.sessions[0].id;
                }
                this.save();
            },

            getCurrentSession() {
                return this.sessions.find(s => s.id === this.currentSessionId);
            },

            getCurrentMessages() {
                const session = this.getCurrentSession();
                return session ? session.messages : [];
            },

            getCurrentTitle() {
                const session = this.getCurrentSession();
                return session ? session.title : 'Chat';
            },

            scrollToBottom() {
                setTimeout(() => {
                    const container = document.getElementById('chat-container');
                    if (container) {
                        container.scrollTop = container.scrollHeight;
                    }
                }, 50);
            },

            focusInput() {
                setTimeout(() => {
                    if (this.$refs.chatInput) this.$refs.chatInput.focus();
                }, 50);
            },

            async sendPrompt(promptText) {
                if (!promptText.trim() || this.isLoading) return;
                
                const session = this.getCurrentSession();
                if (!session) return;

                const userText = promptText.trim();
                
                if (session.messages.length === 0 && session.title === 'New Chat') {
                    session.title = userText.length > 30 ? userText.substring(0, 30) + '...' : userText;
                }

                session.messages.push({ role: 'user', text: userText });
                this.inputValue = '';
                this.isLoading = true;
                this.save();
                this.scrollToBottom();

                try {
                    const response = await fetch('/api/chat', {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            prompt: userText,
                            messages: session.messages.slice(0, -1)
                        })
                    });

                    const data = await response.json();
                    if (!response.ok) throw new Error(data.error || 'Server error');
                    
                    session.messages.push({ role: 'ai', text: data.text });
                } catch (error) {
                    session.messages.push({ role: 'ai', text: 'Error: ' + error.message });
                } finally {
                    this.isLoading = false;
                    this.save();
                    this.scrollToBottom();
                    this.focusInput();
                }
            },
            
            submitForm(e) {
                if(e && e.shiftKey) return;
                this.sendPrompt(this.inputValue);
            }
        }));
    });
</script>
@endpush
