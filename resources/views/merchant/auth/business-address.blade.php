<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    @include('partials.theme')
    <title>Business Address - BeAurex</title>
    <style>
        input:focus, select:focus { outline: none; border-color: #b00000; box-shadow: 0 0 0 3px rgba(176, 0, 0, 0.08); }
    </style>
</head>
<body class="bg-[#f1f5f9] text-[#0f172a] antialiased min-h-[100dvh] flex flex-col items-center justify-center p-4">
    <div class="w-full max-w-sm">
        
                <div class="bg-white rounded-2xl border border-[#e2e8f0] shadow-sm overflow-hidden">
                    <div class="flex flex-col items-center pt-8 pb-6 px-5 sm:px-8 bg-red-50">
                        <div
                            class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center text-[#EF4444] shadow-sm mb-4">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-black text-[#0f172a] mb-1 text-center">Business Address</h2>
                        <p class="text-xs font-medium text-[#475569] text-center">Add your business location</p>
                    </div>

                    <div class="px-7 py-6">
                        <form action="{{ route('merchant.business-address') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-2">Address Line 1</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                        </svg>
                                    </div>
                                    <input type="text" name="address_line_1" placeholder="Enter address line 1"
                                        required
                                        class="w-full bg-[#f1f5f9] border border-[#e2e8f0] rounded-xl pl-10 pr-4 py-2.5 text-sm font-medium text-[#0f172a] transition">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-2">Address Line 2
                                    (Optional)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                            </path>
                                        </svg>
                                    </div>
                                    <input type="text" name="address_line_2" placeholder="Enter address line 2"
                                        class="w-full bg-[#f1f5f9] border border-[#e2e8f0] rounded-xl pl-10 pr-4 py-2.5 text-sm font-medium text-[#0f172a] transition">
                                </div>
                            </div>
                            
<div class="flex space-x-3">
    <!-- State on the left -->
    <div class="w-1/2">
        <label class="block text-xs font-bold text-slate-700 mb-2">State</label>
        <div class="relative">
            <select name="state" id="stateDropdown" required class="w-full bg-[#f1f5f9] border border-[#e2e8f0] rounded-xl px-4 py-2.5 text-sm font-medium text-[#0f172a] transition appearance-none cursor-pointer">
                <option value="" disabled selected>Select state</option>
                <option value="Andhra Pradesh">Andhra Pradesh</option>
                <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                <option value="Assam">Assam</option>
                <option value="Bihar">Bihar</option>
                <option value="Chhattisgarh">Chhattisgarh</option>
                <option value="Goa">Goa</option>
                <option value="Gujarat">Gujarat</option>
                <option value="Haryana">Haryana</option>
                <option value="Himachal Pradesh">Himachal Pradesh</option>
                <option value="Jharkhand">Jharkhand</option>
                <option value="Karnataka">Karnataka</option>
                <option value="Kerala">Kerala</option>
                <option value="Madhya Pradesh">Madhya Pradesh</option>
                <option value="Maharashtra">Maharashtra</option>
                <option value="Manipur">Manipur</option>
                <option value="Meghalaya">Meghalaya</option>
                <option value="Mizoram">Mizoram</option>
                <option value="Nagaland">Nagaland</option>
                <option value="Odisha">Odisha</option>
                <option value="Punjab">Punjab</option>
                <option value="Rajasthan">Rajasthan</option>
                <option value="Sikkim">Sikkim</option>
                <option value="Tamil Nadu">Tamil Nadu</option>
                <option value="Telangana">Telangana</option>
                <option value="Tripura">Tripura</option>
                <option value="Uttar Pradesh">Uttar Pradesh</option>
                <option value="Uttarakhand">Uttarakhand</option>
                <option value="West Bengal">West Bengal</option>
                <option value="Delhi">Delhi</option>
            </select>
            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>
        </div>
    </div>
    
    <!-- City on the right -->
    <div class="w-1/2">
        <label class="block text-xs font-bold text-slate-700 mb-2">City</label>
        <div class="relative">
            <select name="city" id="cityDropdown" required class="w-full bg-[#f1f5f9] border border-[#e2e8f0] rounded-xl px-4 py-2.5 text-sm font-medium text-[#0f172a] transition appearance-none cursor-pointer" disabled>
                <option value="" disabled selected>Select city</option>
            </select>
            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>
        </div>
    </div>
</div>
<div>
    <label class="block text-xs font-bold text-slate-700 mb-2">PIN Code</label>
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                            </path>
                                        </svg>
                                    </div>
                                    <input type="text" name="pin_code" placeholder="Enter PIN code" required
                                        class="w-full bg-[#f1f5f9] border border-[#e2e8f0] rounded-xl pl-10 pr-4 py-2.5 text-sm font-medium text-[#0f172a] transition">
                                </div>
                            </div>
                            <div class="pt-2">
                                <button type="submit"
                                    class="w-full text-white font-bold py-3 rounded-xl text-sm transition"
                                    style="background:#b00000" onmouseover="this.style.background='#8a0000'"
                                    onmouseout="this.style.background='#b00000'">
                                    Continue
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- 7-Step Progress indicator -->
                <div class="mt-5 bg-white border border-[#e2e8f0] rounded-2xl px-4 py-4 shadow-sm">
                    <p class="text-[10px] font-bold text-slate-700 mb-3 text-center">Merchant Onboarding</p>
                    <div class="flex items-center justify-between">
                        <div
                            class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-black text-white bg-[#22C55E]">
                            ✓</div>
                        <div class="flex-1 h-px mx-1 bg-green-200"></div>
                        <div
                            class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-black text-white bg-[#22C55E]">
                            ✓</div>
                        <div class="flex-1 h-px mx-1 bg-green-200"></div>
                        <div
                            class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-black text-white bg-[#22C55E]">
                            ✓</div>
                        <div class="flex-1 h-px mx-1 bg-green-200"></div>
                        <div
                            class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-black text-white bg-[#22C55E]">
                            ✓</div>
                        <div class="flex-1 h-px mx-1 bg-green-200"></div>
                        <div
                            class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-black text-white bg-[#22C55E]">
                            ✓</div>
                        <div class="flex-1 h-px mx-1 bg-green-200"></div>
                        <div
                            class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-black text-white bg-[#b00000] ring-2 ring-red-100 ring-offset-1">
                            6</div>
                        <div class="flex-1 h-px mx-1 bg-slate-200"></div>
                        <div
                            class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-bold bg-slate-100 text-slate-400">
                            7</div>
                    </div>
                    <div class="flex justify-between mt-1.5 px-1">
                        <span class="text-[8px] font-semibold text-green-600">Login</span>
                        <span class="text-[8px] font-bold text-[#b00000]">Address</span>
                        <span class="text-[8px] font-medium text-slate-400">Complete</span>
                    </div>
                </div>

            
    </div>

<script>
    const citiesByState = {
        "Andhra Pradesh": ["Visakhapatnam", "Vijayawada", "Guntur", "Nellore", "Tirupati"],
        "Arunachal Pradesh": ["Itanagar", "Tawang", "Ziro", "Pasighat", "Roing"],
        "Assam": ["Guwahati", "Silchar", "Dibrugarh", "Jorhat", "Nagaon"],
        "Bihar": ["Patna", "Gaya", "Bhagalpur", "Muzaffarpur", "Purnia"],
        "Chhattisgarh": ["Raipur", "Bhilai", "Bilaspur", "Korba", "Rajnandgaon"],
        "Goa": ["Panaji", "Margao", "Vasco da Gama", "Mapusa", "Ponda"],
        "Gujarat": ["Ahmedabad", "Surat", "Vadodara", "Rajkot", "Bhavnagar"],
        "Haryana": ["Faridabad", "Gurugram", "Panipat", "Ambala", "Yamunanagar"],
        "Himachal Pradesh": ["Shimla", "Mandi", "Solan", "Dharamshala", "Kullu"],
        "Jharkhand": ["Ranchi", "Jamshedpur", "Dhanbad", "Bokaro", "Deoghar"],
        "Karnataka": ["Bengaluru", "Mysuru", "Hubballi", "Mangaluru", "Belagavi"],
        "Kerala": ["Thiruvananthapuram", "Kochi", "Kozhikode", "Kollam", "Thrissur"],
        "Madhya Pradesh": ["Indore", "Bhopal", "Jabalpur", "Gwalior", "Ujjain"],
        "Maharashtra": ["Mumbai", "Pune", "Nagpur", "Nashik", "Aurangabad"],
        "Manipur": ["Imphal", "Thoubal", "Bishnupur", "Churachandpur", "Kakching"],
        "Meghalaya": ["Shillong", "Tura", "Nongstoin", "Jowai", "Baghmara"],
        "Mizoram": ["Aizawl", "Lunglei", "Saiha", "Champhai", "Kolasib"],
        "Nagaland": ["Kohima", "Dimapur", "Mokokchung", "Tuensang", "Wokha"],
        "Odisha": ["Bhubaneswar", "Cuttack", "Rourkela", "Brahmapur", "Sambalpur"],
        "Punjab": ["Ludhiana", "Amritsar", "Jalandhar", "Patiala", "Bathinda"],
        "Rajasthan": ["Jaipur", "Jodhpur", "Kota", "Bikaner", "Ajmer"],
        "Sikkim": ["Gangtok", "Namchi", "Geyzing", "Mangan", "Singtam"],
        "Tamil Nadu": ["Chennai", "Coimbatore", "Madurai", "Tiruchirappalli", "Salem"],
        "Telangana": ["Hyderabad", "Warangal", "Nizamabad", "Karimnagar", "Khammam"],
        "Tripura": ["Agartala", "Dharmanagar", "Kailashahar", "Udaipur", "Belonia"],
        "Uttar Pradesh": ["Lucknow", "Kanpur", "Ghaziabad", "Agra", "Varanasi"],
        "Uttarakhand": ["Dehradun", "Haridwar", "Roorkee", "Haldwani", "Rudrapur"],
        "West Bengal": ["Kolkata", "Asansol", "Siliguri", "Durgapur", "Bardhaman"],
        "Delhi": ["New Delhi", "North Delhi", "South Delhi", "East Delhi", "West Delhi"]
    };

    const stateDropdown = document.getElementById("stateDropdown");
    const cityDropdown = document.getElementById("cityDropdown");

    stateDropdown.addEventListener("change", function() {
        const state = this.value;
        cityDropdown.innerHTML = '<option value="" disabled selected>Select city</option>';
        
        if (state && citiesByState[state]) {
            citiesByState[state].forEach(city => {
                const option = document.createElement("option");
                option.value = city;
                option.textContent = city;
                cityDropdown.appendChild(option);
            });
            cityDropdown.disabled = false;
        } else {
            cityDropdown.disabled = true;
        }
    });
</script>
</body>
</html>
