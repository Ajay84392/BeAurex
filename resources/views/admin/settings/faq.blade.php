@extends('layouts.admin')

@section('title', 'FAQ Editor')

@section('content')
<div class="flex-1 min-w-0 overflow-auto p-4 sm:p-6 lg:p-10">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-[#0f172a] mb-1">FAQ Editor</h1>
        <div class="text-xs text-[#475569] font-medium flex items-center space-x-1">
            <a href="/admin/dashboard" class="hover:text-[#b00000] transition">Home</a>
            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
            <a href="/admin/settings" class="hover:text-[#b00000] transition">Settings</a>
            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-[#0f172a]">FAQ Editor</span>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-bold flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-[#e2e8f0] overflow-hidden">
        
        <!-- Filters Bar -->
        <div class="p-4 border-b border-[#e2e8f0] bg-[#f1f5f9]/50 flex flex-col md:flex-row gap-4 items-center justify-between">
            <form action="{{ route('admin.faq.index') }}" method="GET" class="flex-1 flex flex-col md:flex-row gap-4 w-full">
                <!-- Search -->
                <div class="relative w-full md:w-1/3">
                    <svg class="w-4 h-4 absolute left-3 top-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" name="search" placeholder="Search FAQ by question or keyword..." value="{{ request('search') }}"
                        class="w-full bg-white border border-[#e2e8f0] rounded-xl pl-9 pr-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500">
                </div>
                
                <!-- Category Filter -->
                <select name="category" onchange="this.form.submit()" class="w-full md:w-48 bg-white border border-[#e2e8f0] rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500">
                    <option value="All Categories" {{ request('category') === 'All Categories' ? 'selected' : '' }}>All Categories</option>
                    <option value="General" {{ request('category') === 'General' ? 'selected' : '' }}>General</option>
                    <option value="Merchant" {{ request('category') === 'Merchant' ? 'selected' : '' }}>Merchant</option>
                    <option value="Rewards" {{ request('category') === 'Rewards' ? 'selected' : '' }}>Rewards</option>
                    <option value="Integration" {{ request('category') === 'Integration' ? 'selected' : '' }}>Integration</option>
                    <option value="Support" {{ request('category') === 'Support' ? 'selected' : '' }}>Support</option>
                </select>

                <!-- Status Filter -->
                <select name="status" onchange="this.form.submit()" class="w-full md:w-40 bg-white border border-[#e2e8f0] rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500">
                    <option value="All Status" {{ request('status') === 'All Status' ? 'selected' : '' }}>All Status</option>
                    <option value="Published" {{ request('status') === 'Published' ? 'selected' : '' }}>Published</option>
                    <option value="Draft" {{ request('status') === 'Draft' ? 'selected' : '' }}>Draft</option>
                    <option value="Unpublished" {{ request('status') === 'Unpublished' ? 'selected' : '' }}>Unpublished</option>
                </select>
            </form>

            <button onclick="document.getElementById('addFaqModal').classList.remove('hidden')" class="w-full md:w-auto px-4 py-2 bg-white border border-red-200 text-[#b00000] font-bold text-sm rounded-xl hover:bg-red-50 transition whitespace-nowrap">
                + Add FAQ
            </button>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <div class="overflow-x-auto w-full"><table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#f1f5f9] border-b border-[#e2e8f0] text-xs font-bold text-[#475569] uppercase tracking-wider">
                        <th class="px-6 py-4">#</th>
                        <th class="px-6 py-4">Question</th>
                        <th class="px-6 py-4">Category</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Order</th>
                        <th class="px-6 py-4">Last Updated</th>
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($faqs as $index => $faq)
                    <tr class="hover:bg-[#f1f5f9]/50 transition">
                        <td class="px-6 py-4 text-[#475569] font-medium">{{ $faqs->firstItem() + $index }}</td>
                        <td class="px-6 py-4 font-semibold text-[#0f172a]">{{ $faq->question }}</td>
                        <td class="px-6 py-4 text-[#475569]">{{ $faq->category }}</td>
                        <td class="px-6 py-4">
                            @if($faq->status === 'Published')
                                <span class="px-2.5 py-1 text-[10px] font-bold tracking-wider text-emerald-700 bg-emerald-100 rounded-full">Published</span>
                            @elseif($faq->status === 'Draft')
                                <span class="px-2.5 py-1 text-[10px] font-bold tracking-wider text-amber-700 bg-amber-100 rounded-full">Draft</span>
                            @else
                                <span class="px-2.5 py-1 text-[10px] font-bold tracking-wider text-[#8a0000] bg-red-100 rounded-full">Unpublished</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-[#475569] font-medium">{{ $faq->sort_order }}</td>
                        <td class="px-6 py-4 text-[#475569]">{{ $faq->updated_at->format('M d, Y h:i A') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center space-x-2">
                                <button onclick="openPreviewModal(`{{ addslashes($faq->question) }}`, `{{ addslashes($faq->answer) }}`)" class="p-1.5 rounded text-slate-400 hover:text-[#475569] hover:bg-slate-100 transition" title="Preview">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </button>
                                <button onclick="openEditModal({{ $faq->id }}, `{{ addslashes($faq->question) }}`, `{{ addslashes($faq->answer) }}`, `{{ $faq->category }}`, `{{ $faq->status }}`, {{ $faq->sort_order }})" class="p-1.5 rounded text-slate-400 hover:text-red-600 hover:bg-red-50 transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <form action="{{ route('admin.faq.destroy', $faq->id) }}" method="POST" onsubmit="return confirm('Delete this FAQ?')">
                                    @csrf
                                    @method("DELETE")
                                    <button type="submit" class="p-1.5 rounded text-slate-400 hover:text-[#b00000] hover:bg-red-50 transition" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-[#475569]">
                            <div class="mb-2">
                                <svg class="w-8 h-8 mx-auto text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            No FAQs found matching your criteria.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table></div>
        </div>
        
        <!-- Pagination -->
        @if($faqs->hasPages())
        <div class="px-6 py-4 border-t border-[#e2e8f0]">
            {{ $faqs->links() }}
        </div>
        @else
        <div class="px-6 py-4 border-t border-[#e2e8f0] text-sm text-[#475569] text-center md:text-left">
            Showing {{ $faqs->firstItem() ?? 0 }} to {{ $faqs->lastItem() ?? 0 }} of {{ $faqs->total() }} entries
        </div>
        @endif

    </div>

</div>

<!-- ===== ADD FAQ MODAL ===== -->
<div id="addFaqModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" style="background:rgba(0,0,0,0.5)">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg">
        <div class="flex items-center justify-between px-6 py-5 border-b border-[#e2e8f0]">
            <h2 class="text-lg font-black text-[#0f172a]">Add New FAQ</h2>
            <button onclick="document.getElementById('addFaqModal').classList.add('hidden')" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form action="{{ route('admin.faq.store') }}" method="POST" class="p-6 space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Question <span class="text-[#EF4444]">*</span></label>
                <input type="text" name="question" placeholder="What is BeAurex?" required class="w-full bg-[#f1f5f9] border border-[#e2e8f0] rounded-xl px-4 py-3 text-sm font-medium text-[#0f172a] placeholder-slate-400 focus:outline-none focus:border-[#b00000] focus:ring-2 focus:ring-red-600/10 transition">
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Category <span class="text-[#EF4444]">*</span></label>
                    <select name="category" required class="w-full bg-[#f1f5f9] border border-[#e2e8f0] rounded-xl px-4 py-3 text-sm font-medium text-[#0f172a] focus:outline-none focus:border-[#b00000] focus:ring-2 focus:ring-red-600/10 transition">
                        <option value="General">General</option>
                        <option value="Merchant">Merchant</option>
                        <option value="Rewards">Rewards</option>
                        <option value="Integration">Integration</option>
                        <option value="Support">Support</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Status <span class="text-[#EF4444]">*</span></label>
                    <select name="status" required class="w-full bg-[#f1f5f9] border border-[#e2e8f0] rounded-xl px-4 py-3 text-sm font-medium text-[#0f172a] focus:outline-none focus:border-[#b00000] focus:ring-2 focus:ring-red-600/10 transition">
                        <option value="Published">Published</option>
                        <option value="Draft">Draft</option>
                        <option value="Unpublished">Unpublished</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Display Order (Optional)</label>
                <input type="number" name="sort_order" placeholder="e.g. 1" class="w-full bg-[#f1f5f9] border border-[#e2e8f0] rounded-xl px-4 py-3 text-sm font-medium text-[#0f172a] placeholder-slate-400 focus:outline-none focus:border-[#b00000] focus:ring-2 focus:ring-red-600/10 transition">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Answer <span class="text-[#EF4444]">*</span></label>
                <textarea name="answer" rows="4" placeholder="BeAurex is a..." required class="w-full bg-[#f1f5f9] border border-[#e2e8f0] rounded-xl px-4 py-3 text-sm font-medium text-[#0f172a] placeholder-slate-400 focus:outline-none focus:border-[#b00000] focus:ring-2 focus:ring-red-600/10 transition resize-none"></textarea>
            </div>
            
            <div class="flex items-center justify-end space-x-3 pt-2">
                <button type="button" onclick="document.getElementById('addFaqModal').classList.add('hidden')" class="px-5 py-2.5 rounded-xl border border-[#e2e8f0] text-sm font-bold text-[#475569] hover:bg-[#f1f5f9] transition">Cancel</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl text-white text-sm font-bold transition bg-[#b00000] hover:bg-[#8a0000]">Save FAQ</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== EDIT FAQ MODAL ===== -->
<div id="editFaqModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" style="background:rgba(0,0,0,0.5)">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg">
        <div class="flex items-center justify-between px-6 py-5 border-b border-[#e2e8f0]">
            <h2 class="text-lg font-black text-[#0f172a]">Edit FAQ</h2>
            <button onclick="document.getElementById('editFaqModal').classList.add('hidden')" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form id="editFaqForm" method="POST" class="p-6 space-y-5">
            @csrf
            @method("PUT")
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Question <span class="text-[#EF4444]">*</span></label>
                <input type="text" id="editQuestion" name="question" required class="w-full bg-[#f1f5f9] border border-[#e2e8f0] rounded-xl px-4 py-3 text-sm font-medium text-[#0f172a] focus:outline-none focus:border-[#b00000] focus:ring-2 focus:ring-red-600/10 transition">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Category <span class="text-[#EF4444]">*</span></label>
                    <select id="editCategory" name="category" required class="w-full bg-[#f1f5f9] border border-[#e2e8f0] rounded-xl px-4 py-3 text-sm font-medium text-[#0f172a] focus:outline-none focus:border-[#b00000] focus:ring-2 focus:ring-red-600/10 transition">
                        <option value="General">General</option>
                        <option value="Merchant">Merchant</option>
                        <option value="Rewards">Rewards</option>
                        <option value="Integration">Integration</option>
                        <option value="Support">Support</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Status <span class="text-[#EF4444]">*</span></label>
                    <select id="editStatus" name="status" required class="w-full bg-[#f1f5f9] border border-[#e2e8f0] rounded-xl px-4 py-3 text-sm font-medium text-[#0f172a] focus:outline-none focus:border-[#b00000] focus:ring-2 focus:ring-red-600/10 transition">
                        <option value="Published">Published</option>
                        <option value="Draft">Draft</option>
                        <option value="Unpublished">Unpublished</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Display Order</label>
                <input type="number" id="editSortOrder" name="sort_order" class="w-full bg-[#f1f5f9] border border-[#e2e8f0] rounded-xl px-4 py-3 text-sm font-medium text-[#0f172a] focus:outline-none focus:border-[#b00000] focus:ring-2 focus:ring-red-600/10 transition">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Answer <span class="text-[#EF4444]">*</span></label>
                <textarea id="editAnswer" name="answer" rows="4" required class="w-full bg-[#f1f5f9] border border-[#e2e8f0] rounded-xl px-4 py-3 text-sm font-medium text-[#0f172a] focus:outline-none focus:border-[#b00000] focus:ring-2 focus:ring-red-600/10 transition resize-none"></textarea>
            </div>
            
            <div class="flex items-center justify-end space-x-3 pt-2">
                <button type="button" onclick="document.getElementById('editFaqModal').classList.add('hidden')" class="px-5 py-2.5 rounded-xl border border-[#e2e8f0] text-sm font-bold text-[#475569] hover:bg-[#f1f5f9] transition">Cancel</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl text-white text-sm font-bold transition bg-[#b00000] hover:bg-[#8a0000]">Update FAQ</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== PREVIEW FAQ MODAL ===== -->
<div id="previewFaqModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" style="background:rgba(0,0,0,0.5)">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg">
        <div class="flex items-center justify-between px-6 py-5 border-b border-[#e2e8f0]">
            <h2 class="text-lg font-black text-[#0f172a]">FAQ Preview</h2>
            <button onclick="document.getElementById('previewFaqModal').classList.add('hidden')" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6">
            <h3 id="previewQuestion" class="text-xl font-bold text-[#0f172a] mb-4"></h3>
            <p id="previewAnswer" class="text-sm text-[#475569] leading-relaxed"></p>
        </div>
        <div class="px-6 py-4 bg-[#f1f5f9] border-t border-slate-100 rounded-b-2xl flex justify-end">
            <button onclick="document.getElementById('previewFaqModal').classList.add('hidden')" class="px-5 py-2 bg-white border border-[#e2e8f0] rounded-xl text-sm font-bold text-slate-700 hover:bg-[#f1f5f9] transition">Close</button>
        </div>
    </div>
</div>

@endsection

@section("scripts")
<script>
function openEditModal(id, question, answer, category, status, sortOrder) {
    document.getElementById("editFaqForm").action = "/admin/settings/faq/" + id;
    document.getElementById("editQuestion").value = question;
    document.getElementById("editAnswer").value = answer;
    document.getElementById("editCategory").value = category;
    document.getElementById("editStatus").value = status;
    document.getElementById("editSortOrder").value = sortOrder;
    document.getElementById("editFaqModal").classList.remove("hidden");
}

function openPreviewModal(question, answer) {
    document.getElementById("previewQuestion").innerText = question;
    document.getElementById("previewAnswer").innerText = answer;
    document.getElementById("previewFaqModal").classList.remove("hidden");
}
</script>
@endsection






