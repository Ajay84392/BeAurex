@extends('layouts.merchant')

@section('title', 'Create Offer')

@section('content')
<div class="bg-slate-50 md:bg-transparent min-h-[100dvh] md:min-h-0 relative pb-24 md:pb-10" x-data="createOfferApp()">
    
    <!-- Red Header Section -->
    <div class="bg-[#8a0000] px-6 pt-10 pb-20 md:pb-24 text-white relative md:rounded-3xl md:shadow-md">
        <div class="flex justify-between items-center mb-6 w-full mx-auto">
            <h1 class="text-xl md:text-2xl font-black tracking-tight md:mx-0">Create Offer</h1>
            <div class="flex items-center space-x-4">
                <button @click.prevent="isCreating ? (isCreating = false) : resetForm()" class="hidden md:flex items-center space-x-1.5 bg-white text-[#b00000] px-4 py-2 rounded-xl text-sm font-bold hover:bg-red-50 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    <span x-text="isCreating ? 'Cancel' : 'Create Offer'"></span>
                </button>
                <!-- Profile Avatar (Visible on Mobile) -->
                <a href="/merchant/profile" class="w-10 h-10 rounded-full border border-red-400/50 flex items-center justify-center hover:bg-white/10 transition md:hidden shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content Wrapper (Overlapping the red header) -->
    <div class="bg-slate-50 md:bg-white rounded-t-3xl md:rounded-3xl -mt-8 md:-mt-12 relative z-20 px-5 md:px-8 shadow-[0_-10px_20px_-5px_rgba(0,0,0,0.05)] md:shadow-lg min-h-[500px] w-full md:border md:border-slate-100 pb-10">
        
        <div class="pt-6 w-full">

            <!-- Inline Empty State (No Offers) -->
            <div x-show="!isCreating && existingOffers.length === 0" class="w-full max-w-2xl mx-auto flex flex-col items-center justify-center py-16 px-4 bg-white rounded-3xl border border-slate-100 shadow-sm text-center mb-8" style="display: none;">
                <div class="w-20 h-20 rounded-full bg-red-50 text-[#b00000] flex items-center justify-center mb-6">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
                </div>
                <h3 class="text-xl font-black text-slate-900 mb-2">No Offers Yet</h3>
                <p class="text-sm font-semibold text-slate-500 mb-8 max-w-sm mx-auto">
                    You haven't created any offers. Get started by creating your first reward for your customers!
                </p>
                <button @click.prevent="resetForm()" class="bg-[#b00000] text-white px-8 py-3.5 rounded-2xl font-bold hover:bg-[#8a0000] transition shadow-md flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    <span>Create New Offer</span>
                </button>
            </div>

            <!-- List View -->
            <div x-show="!isCreating && existingOffers.length > 0" class="w-full max-w-4xl mx-auto mb-8" style="display: none;">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <template x-for="savedOffer in existingOffers" :key="savedOffer.id">
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col hover:shadow-md transition">
                            <div class="h-32 bg-slate-100 relative">
                                <img x-show="savedOffer.image" :src="savedOffer.image" class="w-full h-full object-cover">
                            </div>
                            <div class="p-4 flex-1 flex flex-col">
                                <h3 class="font-bold text-slate-900 text-sm mb-1" x-text="savedOffer.title || 'Untitled Offer'"></h3>
                                <p class="text-xs text-slate-500 line-clamp-2 mb-3 flex-1" x-text="savedOffer.description"></p>
                                <div class="flex items-center justify-between text-[10px] font-semibold text-slate-400 mb-3">
                                    <span x-text="savedOffer.visits + ' Aurex Coin'"></span>
                                    <span x-text="'Expires: ' + (savedOffer.expiry || 'No date')"></span>
                                </div>
                                <div class="pt-3 border-t border-slate-100 flex items-center justify-between mt-auto">
                                    <button @click.prevent="previewExistingOffer(savedOffer)" class="text-[11px] font-bold text-[#b00000] hover:text-[#8a0000] transition flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        Preview
                                    </button>
                                    <button @click.prevent="editOffer(savedOffer)" class="text-[11px] font-bold text-slate-600 hover:text-slate-900 transition flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        Edit
                                    </button>
                                    <form method="POST" :action="`/merchant/offer/${savedOffer.id}`" class="inline m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Are you sure you want to delete this offer?')" class="text-[11px] font-bold text-red-500 hover:text-red-700 transition flex items-center">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Loop over offers -->
            <div x-show="isCreating" class="flex flex-col items-center space-y-6 w-full" style="display: none;">
                <template x-for="(offer, index) in offers" :key="offer.id">
                <div class="w-full max-w-2xl bg-white rounded-2xl shadow-sm border border-[#e2e8f0] p-5 relative transition-all">
                    <!-- Offer Details Form -->
                    <div class="space-y-6">
                            
                            <!-- Offer Image -->
                            <div>
                                <h4 class="text-[13px] font-black text-slate-900 mb-0.5">Offer Image</h4>
                                <p class="text-[11px] font-semibold text-slate-500 mb-3">Upload attractive image for your offer</p>
                                
                                <div class="flex space-x-4">
                                    <!-- Uploaded Preview -->
                                    <div x-show="offer.image" class="relative w-[110px] h-[110px] rounded-2xl flex items-center justify-center shadow-md shrink-0 border border-slate-200 overflow-hidden bg-slate-50" style="display: none;">
                                        <div @click.prevent="offer.image = null; $refs.fileInput.value = ''" class="absolute -top-2 -right-2 w-6 h-6 bg-[#8a0000] rounded-full flex items-center justify-center border-2 border-white cursor-pointer shadow-sm hover:scale-105 transition z-10">
                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </div>
                                        <img :src="offer.image" class="w-full h-full object-cover" alt="Offer Preview">
                                    </div>
                                    <!-- Upload Button -->
                                    <div x-show="!offer.image" @click="$refs.fileInput.click()" class="w-[110px] h-[110px] border-2 border-dashed border-slate-300 rounded-2xl flex flex-col items-center justify-center cursor-pointer hover:border-[#b00000] hover:bg-red-50 transition p-2 text-center shrink-0 group">
                                        <svg class="w-6 h-6 text-[#b00000] mb-2 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span class="text-[10px] font-bold text-[#b00000] leading-tight mb-1">Upload Image</span>
                                        <span class="text-[8px] font-semibold text-slate-400 leading-tight">JPG, PNG up to 3MB</span>
                                    </div>
                                    <input type="file" x-ref="fileInput" class="hidden" accept="image/png, image/jpeg, image/jpg" @change="handleImageUpload($event, index)">
                                </div>
                            </div>

                            <!-- Title -->
                            <div>
                                <h4 class="text-[13px] font-black text-slate-900 mb-2">Offer Title</h4>
                                <div class="relative">
                                    <input x-model="offer.title" type="text" maxlength="100" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3.5 text-[13px] font-bold text-slate-900 focus:outline-none focus:border-[#b00000] focus:ring-1 focus:ring-[#b00000] transition placeholder-slate-400 shadow-sm pr-16" placeholder="e.g. 30% OFF on Next Purchase">
                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
                                        <span class="text-[11px] font-bold text-slate-400" x-text="(offer.title?.length || 0) + '/100'"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div>
                                <h4 class="text-[13px] font-black text-slate-900 mb-2">Offer Description</h4>
                                <div class="relative">
                                    <textarea x-model="offer.description" rows="3" maxlength="200" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3.5 text-[13px] font-bold text-slate-900 focus:outline-none focus:border-[#b00000] focus:ring-1 focus:ring-[#b00000] transition placeholder-slate-400 shadow-sm resize-none pb-8" placeholder="e.g. Get 30% off on your next purchase..."></textarea>
                                    <div class="absolute bottom-3 right-4 pointer-events-none">
                                        <span class="text-[11px] font-bold text-slate-400" x-text="(offer.description?.length || 0) + '/200'"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Aurex Coin & Expiry Grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Stamps -->
                                <div class="bg-white border border-slate-100 rounded-xl p-3 shadow-sm">
                                    <div class="flex items-center space-x-1.5 mb-2.5">
                                        <svg class="w-4 h-4 text-[#b00000]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        <h4 class="text-[11px] font-black text-slate-900">Required Aurex Coin</h4>
                                    </div>
                                    <div class="border border-slate-200 rounded-lg p-1.5 flex items-center justify-between mb-2">
                                        <button @click.prevent="if(offer.visits > 1) offer.visits--" class="w-7 h-7 rounded-full bg-slate-100 text-slate-400 hover:bg-slate-200 flex items-center justify-center transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"></path></svg>
                                        </button>
                                        <span class="text-[15px] font-black text-slate-900" x-text="offer.visits">5</span>
                                        <button @click.prevent="offer.visits++" class="w-7 h-7 rounded-full bg-[#b00000] text-white hover:bg-[#8a0000] flex items-center justify-center transition shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                                        </button>
                                    </div>
                                    <p class="text-[9px] font-semibold text-slate-500 leading-snug">Customer needs to collect <span x-text="offer.visits"></span> Aurex Coin to unlock this offer</p>
                                </div>
                                
                                <!-- Expiry -->
                                <div class="bg-white border border-slate-100 rounded-xl p-3 shadow-sm">
                                    <div class="flex items-center space-x-1.5 mb-2.5">
                                        <svg class="w-4 h-4 text-[#b00000]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <h4 class="text-[11px] font-black text-slate-900">Expiry</h4>
                                    </div>
                                    <div class="relative mb-2">
                                        <input type="date" x-model="offer.expiry" class="w-full bg-white border border-slate-200 rounded-lg pl-3 pr-8 py-2 text-[13px] font-bold text-slate-900 focus:outline-none focus:border-[#b00000] focus:ring-1 focus:ring-[#b00000] transition cursor-pointer">
                                    </div>
                                    <p class="text-[9px] font-semibold text-slate-500 leading-snug">Offer will expire on <span x-text="offer.expiry || 'the selected date'"></span></p>
                                </div>
                            </div>
                            
                            <!-- Info Box -->
                            <div class="bg-orange-50/70 border border-orange-100/50 rounded-xl p-3 flex items-start space-x-3 shadow-sm">
                                <svg class="w-5 h-5 text-[#b00000] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 109.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1114.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"></path></svg>
                                <p class="text-[11px] font-bold text-slate-700 leading-snug pt-0.5">This offer will be available for all customers once they collect the required Aurex Coin.</p>
                            </div>

                        </div>
                </div> <!-- End Offer Card -->
                </template>
            </div> <!-- End Grid -->

            <!-- Action Buttons Area -->
            <div x-show="isCreating" class="w-full mt-10 grid grid-cols-1 md:grid-cols-2 gap-4 px-2 md:px-0" style="display: none;">
                <!-- Save Form -->
                <form id="saveForm" action="{{ route('merchant.create-offer.store') }}" method="POST" class="w-full h-full">
                    @csrf
                    <input type="hidden" name="rewards_json" :value="JSON.stringify(offers)">
                    <button type="submit" class="w-full h-full bg-[#8a0000] text-white font-bold py-3.5 rounded-xl hover:bg-[#700000] transition shadow-md text-[14px]">
                        Save Offer
                    </button>
                </form>
                
                <!-- View Preview Button -->
                <div class="flex flex-col justify-center">
                    <button @click.prevent="openPreview()" class="w-full bg-white border border-[#b00000] text-[#b00000] font-bold py-3.5 rounded-xl flex items-center justify-center space-x-2 hover:bg-red-50 transition shadow-sm text-[14px]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        <span>View Offer Preview</span>
                    </button>
                    <p class="text-center text-[10px] font-semibold text-slate-400 mt-1.5">See how this offer will appear</p>
                </div>
            </div>

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
                            <span x-text="(previewData?.visits || 5) + ' Aurex Coin Required'"></span>
                        </div>
                        <div class="flex items-center text-[9px] font-bold text-slate-600 shrink-0">
                            <svg class="w-3 h-3 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span x-text="'Expires on ' + (previewData?.expiry || 'No date set')"></span>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>



</div>

<script>
    function createOfferApp() {
        return {
            offers: @json($offers),
            existingOffers: @json($existingOffers ?? []),
            isCreating: false,
            showDeleteConfirm: false,
            deleteIndex: null,
            activeOfferIndex: 0,
            previewModal: false,
            previewData: null,
            showWelcomeModal: false,
            init() {
                // Initialize state
            },
            resetForm() {
                this.offers = [{
                    id: Date.now(),
                    title: '',
                    description: '',
                    visits: 5,
                    expiry: '',
                    image: null
                }];
                this.isCreating = true;
            },
            editOffer(savedOffer) {
                this.offers = [{
                    id: savedOffer.id,
                    title: savedOffer.title,
                    description: savedOffer.description,
                    visits: savedOffer.visits,
                    expiry: savedOffer.expiry,
                    image: savedOffer.image
                }];
                this.isCreating = true;
            },
            previewExistingOffer(savedOffer) {
                this.previewData = {
                    title: savedOffer.title,
                    description: savedOffer.description,
                    visits: savedOffer.visits,
                    expiry: savedOffer.expiry,
                    image: savedOffer.image
                };
                this.previewModal = true;
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
            handleImageUpload(event, index) {
                const file = event.target.files[0];
                if (!file) return;

                const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!validTypes.includes(file.type)) {
                    alert('Only JPG, JPEG, and PNG formats are allowed.');
                    event.target.value = '';
                    return;
                }
                
                if (file.size > 3 * 1024 * 1024) {
                    alert('Image size cannot exceed 3MB.');
                    event.target.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.offers[index].image = e.target.result;
                };
                reader.readAsDataURL(file);
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
