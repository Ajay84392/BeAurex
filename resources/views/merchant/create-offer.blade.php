@extends('layouts.merchant')

@section('title', 'Create Offer')

@section('content')
<div class="bg-slate-50 md:bg-transparent min-h-[100dvh] md:min-h-0 relative pb-24 md:pb-0" x-data="createOfferApp()">
    
    <!-- Red Header Section -->
    <div class="bg-[#8a0000] px-6 pt-10 pb-20 md:pb-24 text-white relative">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-xl font-black tracking-tight mx-auto">Create Offer</h1>
            <!-- Profile Avatar (Visible on Mobile) -->
            <a href="/merchant/profile" class="w-10 h-10 rounded-full border border-red-400/50 flex items-center justify-center hover:bg-white/10 transition md:hidden shrink-0 absolute right-6 top-10">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path></svg>
            </a>
        </div>
    </div>

    <!-- Main Content Wrapper (Overlapping the red header) -->
    <div class="bg-slate-50 md:bg-transparent rounded-t-3xl -mt-8 relative z-20 px-5 md:px-8 shadow-[0_-10px_20px_-5px_rgba(0,0,0,0.05)] md:shadow-none min-h-[500px]">
        
        <div class="pt-6 max-w-[800px] mx-auto">

            <!-- Loop over offers -->
            <template x-for="(offer, index) in offers" :key="offer.id">
                <div class="bg-white rounded-2xl shadow-sm border border-[#e2e8f0] p-5 mb-5 relative">
                    <!-- Action buttons (Delete/Expand) -->
                    <div class="absolute top-4 right-4 flex items-center space-x-2">
                        <button @click.prevent="removeOffer(index)" class="w-8 h-8 rounded-full bg-red-50 text-[#b00000] flex items-center justify-center transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                        <button @click.prevent="activeOfferIndex = activeOfferIndex === index ? null : index" class="w-8 h-8 rounded-full bg-slate-50 text-slate-500 flex items-center justify-center transition">
                            <svg class="w-4 h-4 transform transition-transform" :class="{'rotate-180': activeOfferIndex === index}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                    </div>

                    <!-- Header Preview (Always visible) -->
                    <div class="flex items-center space-x-4 cursor-pointer pr-20" @click="activeOfferIndex = activeOfferIndex === index ? null : index">
                        <div class="w-16 h-16 rounded-xl flex-shrink-0 flex items-center justify-center text-white" :class="offer.type === 'FREE' ? 'bg-slate-900' : 'bg-[#b00000]'">
                            <span class="text-[9px] font-black tracking-widest uppercase text-center leading-none" x-text="offer.title || 'NEW OFFER'"></span>
                        </div>
                        <div>
                            <h3 class="font-black text-[15px] text-slate-900 leading-tight" x-text="offer.title || 'Untitled Offer'"></h3>
                            <p class="text-[11px] font-semibold text-[#b00000] mt-1"><span x-text="offer.visits"></span> Aurex Coins Required</p>
                        </div>
                    </div>

                    <!-- Expandable Form Content -->
                    <div x-show="activeOfferIndex === index" x-collapse class="pt-5 mt-5 border-t border-slate-100">
                        <div class="space-y-4">
                            <!-- Title -->
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-2">Offer Title</label>
                                <input x-model="offer.title" type="text" placeholder="e.g. 30% OFF, FREE COFFEE" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-[13px] font-bold text-slate-900 focus:outline-none focus:border-[#b00000] focus:ring-1 focus:ring-[#b00000] transition placeholder-slate-400">
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-2">Description</label>
                                <input x-model="offer.description" type="text" placeholder="e.g. Valid on all beverages" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-[13px] font-bold text-slate-900 focus:outline-none focus:border-[#b00000] focus:ring-1 focus:ring-[#b00000] transition placeholder-slate-400">
                            </div>

                            <!-- Aurex Coins & Expiry Grid -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-2">Required Aurex Coins</label>
                                    <div class="relative">
                                        <select x-model="offer.visits" class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-4 pr-10 py-3 text-[13px] font-bold text-slate-900 focus:outline-none focus:border-[#b00000] focus:ring-1 focus:ring-[#b00000] transition appearance-none">
                                            <option value="1">1 Coin</option>
                                            <option value="2">2 Coins</option>
                                            <option value="3">3 Coins</option>
                                            <option value="4">4 Coins</option>
                                            <option value="5">5 Coins</option>
                                            <option value="6">6 Coins</option>
                                            <option value="7">7 Coins</option>
                                            <option value="8">8 Coins</option>
                                            <option value="9">9 Coins</option>
                                            <option value="10">10 Coins</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-2">Validity (Days)</label>
                                    <input x-model="offer.expiry" type="number" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-[13px] font-bold text-slate-900 focus:outline-none focus:border-[#b00000] focus:ring-1 focus:ring-[#b00000] transition">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Add Button -->
            <button @click.prevent="addOffer()" class="w-full bg-white border border-dashed border-[#b00000] text-[#b00000] font-bold py-4 rounded-2xl flex items-center justify-center space-x-2 hover:bg-red-50 transition mb-8 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                <span>Add Another Offer</span>
            </button>

            <!-- Save Form -->
            <form id="saveForm" action="{{ route('merchant.create-offer.store') }}" method="POST">
                @csrf
                <input type="hidden" name="rewards_json" :value="JSON.stringify(offers)">
                <button type="submit" class="w-full bg-[#b00000] text-white font-black tracking-wide py-4 rounded-2xl hover:bg-[#8a0000] transition shadow-[0_8px_20px_-6px_rgba(176,0,0,0.5)] flex items-center justify-center space-x-2 text-[15px]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                    <span>Save Offer Program</span>
                </button>
            </form>
            
            <!-- View Preview Button -->
            <button @click.prevent="openPreview()" class="w-full bg-white border border-[#b00000] text-[#b00000] font-bold py-3.5 rounded-2xl flex items-center justify-center space-x-2 hover:bg-red-50 transition shadow-sm mt-4 text-[15px]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                <span>View Offer Preview</span>
            </button>
            <p class="text-center text-[11px] font-semibold text-slate-400 mt-2">See how this offer will appear to your customers</p>

        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteConfirm" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.15)] max-w-sm w-full p-6 text-center border border-slate-100 relative" @click.away="showDeleteConfirm = false">
            <div class="w-12 h-12 rounded-full bg-red-50 text-[#b00000] mx-auto flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-[#0f172a] mb-2">Delete Offer?</h3>
            <p class="text-sm text-[#475569] mb-6 leading-relaxed">
                Are you sure you want to delete this offer? This action cannot be undone.
            </p>
            <div class="flex space-x-3">
                <button @click="showDeleteConfirm = false; deleteIndex = null" class="flex-1 py-2.5 bg-white border border-[#e2e8f0] text-slate-700 font-bold rounded-xl hover:bg-slate-50 transition">Cancel</button>
                <button @click="confirmDelete()" class="flex-1 py-2.5 bg-[#b00000] text-white font-bold rounded-xl hover:bg-[#8a0000] transition text-center">Delete</button>
            </div>
        </div>
    </div>
    
    <!-- Offer Preview Modal -->
    <div x-show="previewModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.15)] max-w-sm w-full p-5 relative" @click.away="previewModal = false">
            <button @click="previewModal = false" class="absolute top-4 right-4 w-8 h-8 bg-slate-100 hover:bg-slate-200 rounded-full flex items-center justify-center text-slate-500 transition z-10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <div class="mb-4">
                <span class="text-[#b00000] text-sm font-black tracking-wide">Offer Preview</span> <span class="text-slate-400 text-xs font-semibold ml-1">(As seen by customers)</span>
            </div>
            
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex items-start space-x-4">
                <div class="w-16 h-16 rounded-xl flex-shrink-0 flex items-center justify-center text-white relative overflow-hidden" :class="previewData?.type === 'FREE' ? 'bg-slate-900' : 'bg-[#b00000]'">
                    <img x-show="previewData?.image" :src="previewData?.image" class="absolute inset-0 w-full h-full object-cover">
                    <span x-show="!previewData?.image" class="text-[9px] font-black tracking-widest uppercase text-center leading-none px-1" x-text="previewData?.title || 'NEW OFFER'"></span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-start mb-1">
                        <h4 class="font-black text-slate-900 text-[13px] leading-tight truncate" x-text="previewData?.title || 'Untitled Offer'"></h4>
                        <svg class="w-3.5 h-3.5 text-slate-400 mt-0.5 ml-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                    <p class="text-[10px] text-slate-500 leading-snug mb-2 break-words line-clamp-2" x-text="previewData?.description || 'No description provided'"></p>
                    <div class="flex items-center space-x-3 overflow-hidden">
                        <div class="flex items-center text-[9px] font-bold text-slate-600 shrink-0">
                            <svg class="w-3 h-3 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span x-text="(previewData?.visits || 5) + ' Aurex Coins'"></span>
                        </div>
                        <div class="flex items-center text-[9px] font-bold text-slate-600 shrink-0">
                            <svg class="w-3 h-3 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span x-text="'Valid ' + (previewData?.expiry || 30) + ' Days'"></span>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    function createOfferApp() {
        return {
            offers: @json($offers),
            showDeleteConfirm: false,
            deleteIndex: null,
            activeOfferIndex: 0,
            previewModal: false,
            previewData: null,
            init() {
                if(this.offers.length === 0) {
                    this.addOffer();
                }
            },
            openPreview() {
                if (this.offers.length > 0) {
                    // Preview the currently active offer or the first one
                    let index = this.activeOfferIndex !== null ? this.activeOfferIndex : 0;
                    this.previewData = this.offers[index];
                    this.previewModal = true;
                }
            },
            addOffer() {
                this.offers.push({
                    id: Date.now(),
                    title: '',
                    description: '',
                    visits: 5,
                    expiry: 30,
                    type: 'DISCOUNT'
                });
                this.activeOfferIndex = this.offers.length - 1;
            },
            removeOffer(index) {
                this.deleteIndex = index;
                this.showDeleteConfirm = true;
            },
            confirmDelete() {
                if (this.deleteIndex !== null) {
                    this.offers.splice(this.deleteIndex, 1);
                    this.showDeleteConfirm = false;
                    this.deleteIndex = null;
                }
            }
        }
    }
</script>
@endsection
