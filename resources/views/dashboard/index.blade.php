@extends('layouts.app')

@section('content')
<div class="p-6 sm:p-10 max-w-7xl mx-auto w-full flex flex-col gap-8">
    
    <!-- Header & AI Insight Action -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Tourism Analytics Dashboard</h2>
            <p class="text-slate-500 mt-1">Interactive Decision Support System for International Visitor Data</p>
        </div>
        <button id="btnGenerateInsight" class="flex items-center gap-2 bg-[#0F766E] hover:bg-[#115E59] text-white px-5 py-2.5  font-medium transition-colors shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
            Generate AI Insight
        </button>
    </div>

    <!-- AI Insight Panel (Now a Modal Popup) -->
    <div id="aiInsightPanel" class="hidden fixed inset-0 z-[100] bg-slate-900/50 flex items-center justify-center backdrop-blur-sm transition-opacity">
        <div class="bg-white w-full max-w-3xl p-8 border border-[#0F766E] shadow-xl relative max-h-[80vh] flex flex-col">
            <button id="btnCloseInsight" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 z-10">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
            
            <h3 class="text-xl font-bold text-[#0F766E] mb-6 flex items-center gap-2 shrink-0 border-b pb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                AI Strategic Insight
            </h3>
            
            <div class="overflow-y-auto flex-1 pr-2">
                <div id="aiInsightContent" class="prose prose-sm max-w-none text-slate-700">
                    <!-- Content injected here -->
                </div>
                <div id="aiInsightLoading" class="hidden flex flex-col items-center justify-center py-10 gap-4 text-[#0F766E] font-medium text-sm">
                    <div class="w-8 h-8 border-4 border-[#0F766E] border-t-transparent rounded-full animate-spin"></div>
                    <p>Analyzing dataset and generating strategic recommendations...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white  p-6 border border-slate-200 shadow-sm flex flex-col gap-1">
            <span class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Total Countries</span>
            <span class="text-3xl font-bold text-slate-800">{{ $totalCountries }}</span>
        </div>
        <div class="bg-white  p-6 border border-slate-200 shadow-sm flex flex-col gap-1">
            <span class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Total Visitors YTD</span>
            <span class="text-3xl font-bold text-slate-800">{{ number_format($totalVisitors, 0, ',', '.') }}</span>
        </div>
        <div class="bg-white  p-6 border border-slate-200 shadow-sm flex flex-col gap-1">
            <span class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Highest Visitor</span>
            <span class="text-2xl font-bold text-[#0F766E] truncate" title="{{ $highest['name'] }}">{{ $highest['name'] }}</span>
            <span class="text-sm text-slate-500">{{ number_format($highest['total'], 0, ',', '.') }} pax</span>
        </div>
        <div class="bg-white  p-6 border border-slate-200 shadow-sm flex flex-col gap-1">
            <span class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Lowest Visitor</span>
            <span class="text-2xl font-bold text-amber-600 truncate" title="{{ $lowest['name'] }}">{{ $lowest['name'] }}</span>
            <span class="text-sm text-slate-500">{{ number_format($lowest['total'], 0, ',', '.') }} pax</span>
        </div>
    </div>

    <!-- Geomap -->
    <div class="bg-white  border border-slate-200 shadow-sm overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
            <h3 class="font-bold text-slate-700">Global Visitor Distribution (Interactive Geomap)</h3>
        </div>
        <div id="map" class="w-full h-[500px] z-0"></div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white  border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h3 class="font-bold text-slate-700">Monthly Visitor Trend</h3>
            </div>
            <div class="p-6">
                <canvas id="trendChart" height="250"></canvas>
            </div>
        </div>
        <div class="bg-white  border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h3 class="font-bold text-slate-700">Country Comparison (YTD)</h3>
            </div>
            <div class="p-6">
                <canvas id="comparisonChart" height="250"></canvas>
            </div>
        </div>
    </div>

    <!-- DataTable -->
    <div class="bg-white  border border-slate-200 shadow-sm overflow-hidden flex flex-col mb-10">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
            <h3 class="font-bold text-slate-700">Raw Data (Interactive Table)</h3>
            <button onclick="openModal()" class="bg-[#0F766E] hover:bg-[#115E59] text-white px-4 py-2 text-sm font-medium shadow-sm flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Add Record
            </button>
        </div>
        <div class="p-6">
            <table id="visitorTable" class="display w-full text-sm text-left text-slate-600">
                <thead class="text-xs uppercase bg-slate-100 text-slate-700">
                    <tr>
                        <th>ID</th>
                        <th>Country Name</th>
                        <th>January</th>
                        <th>February</th>
                        <th>March</th>
                        <th>April</th>
                        <th>May</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- CRUD Modal -->
    <div id="crudModal" class="hidden fixed inset-0 z-[100] bg-slate-900/50 flex items-center justify-center backdrop-blur-sm transition-opacity">
        <div class="bg-white w-full max-w-md p-6 border border-[#0F766E] shadow-xl relative">
            <button onclick="closeModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
            <h3 id="modalTitle" class="text-lg font-bold text-[#0F766E] mb-4">Add New Record</h3>
            
            <form id="crudForm" class="flex flex-col gap-4">
                <input type="hidden" id="recordId">
                <div>
                    <label class="block text-sm font-semibold text-slate-600 mb-1">Country Name</label>
                    <input type="text" id="country_name" required class="w-full border border-slate-300 p-2 focus:border-[#0F766E] focus:ring-1 focus:ring-[#0F766E] outline-none">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-600 mb-1">January</label>
                        <input type="number" id="jan" required min="0" class="w-full border border-slate-300 p-2 focus:border-[#0F766E] focus:ring-1 focus:ring-[#0F766E] outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-600 mb-1">February</label>
                        <input type="number" id="feb" required min="0" class="w-full border border-slate-300 p-2 focus:border-[#0F766E] focus:ring-1 focus:ring-[#0F766E] outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-600 mb-1">March</label>
                        <input type="number" id="mar" required min="0" class="w-full border border-slate-300 p-2 focus:border-[#0F766E] focus:ring-1 focus:ring-[#0F766E] outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-600 mb-1">April</label>
                        <input type="number" id="apr" required min="0" class="w-full border border-slate-300 p-2 focus:border-[#0F766E] focus:ring-1 focus:ring-[#0F766E] outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-600 mb-1">May</label>
                        <input type="number" id="may" required min="0" class="w-full border border-slate-300 p-2 focus:border-[#0F766E] focus:ring-1 focus:ring-[#0F766E] outline-none">
                    </div>
                </div>
                <div class="mt-4 flex justify-end gap-2">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 border border-slate-300 text-slate-600 hover:bg-slate-50 font-medium">Cancel</button>
                    <button type="submit" class="bg-[#0F766E] hover:bg-[#115E59] text-white px-4 py-2 font-medium">Save Record</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Data passed from controller
    const allData = @json($allData);
    const months = @json($months);
    const trendData = @json($trendData);
    const countries = @json($countries);
    const totals = @json($totals);

    // Initialize Charts
    document.addEventListener('DOMContentLoaded', function() {
        // Line Chart
        new Chart(document.getElementById('trendChart'), {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Total Visitors',
                    data: trendData,
                    borderColor: '#0F766E',
                    backgroundColor: 'rgba(15, 118, 110, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#0F766E'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });

        // Bar Chart
        new Chart(document.getElementById('comparisonChart'), {
            type: 'bar',
            data: {
                labels: countries,
                datasets: [{
                    label: 'Total Visitors',
                    data: totals,
                    backgroundColor: '#0EA5E9',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });

        // Initialize DataTable
        $('#visitorTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '/ajax/visitors/datatable',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'country_name', name: 'country_name' },
                { data: 'jan', name: 'jan' },
                { data: 'feb', name: 'feb' },
                { data: 'mar', name: 'mar' },
                { data: 'apr', name: 'apr' },
                { data: 'may', name: 'may' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            language: {
                search: "",
                searchPlaceholder: "Search records..."
            }
        });

        // ASEAN region bounds (locked)
        const aseanBounds = L.latLngBounds(
            L.latLng(-15, 90),   // Southwest corner
            L.latLng(30, 145)    // Northeast corner
        );
        
        const map = L.map('map', {
            center: [5, 115],
            zoom: 4,
            minZoom: 3,
            maxZoom: 7,
            maxBounds: aseanBounds,
            maxBoundsViscosity: 1.0,  // Biar ga bisa di-drag keluar bounds
            dragging: false,          // Lock panning — ga bisa digeser
            scrollWheelZoom: true,    // Zoom pake scroll tetep bisa
            touchZoom: true,
            doubleClickZoom: true,
            zoomControl: true
        });
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap contributors &copy; CARTO',
            noWrap: true
        }).addTo(map);

        // Process data for Map coloring
        const countryDataMap = {};
        allData.forEach(item => {
            countryDataMap[item.country_name] = item;
        });

        const maxTotal = Math.max(...totals, 1); // Avoid division by zero

        function getColor(total) {
            // Intensity based on total (Green palette)
            return total > maxTotal * 0.8 ? '#064E3B' :
                   total > maxTotal * 0.5 ? '#047857' :
                   total > maxTotal * 0.2 ? '#10B981' :
                   total > 0            ? '#6EE7B7' :
                                          '#E2E8F0';
        }

        // Fetch GeoJSON and render
        let geojsonLayer;
        fetch('https://raw.githubusercontent.com/johan/world.geo.json/master/countries.geo.json')
            .then(res => res.json())
            .then(data => {
                geojsonLayer = L.geoJSON(data, {
                    filter: function(feature) {
                        // Cuma tampilin negara yang ada di dataset
                        const name = feature.properties.name;
                        return !!(countryDataMap[name] || (name === 'Brunei' && countryDataMap['Brunei']));
                    },
                    style: function (feature) {
                        const name = feature.properties.name;
                        let record = countryDataMap[name];
                        if (!record && name === 'Brunei') record = countryDataMap['Brunei']; 
                        
                        const total = record ? (record.jan + record.feb + record.mar + record.apr + record.may) : 0;
                        return {
                            fillColor: getColor(total),
                            weight: 1,
                            opacity: 1,
                            color: 'white',
                            dashArray: '3',
                            fillOpacity: 0.8
                        };
                    },
                    onEachFeature: function (feature, layer) {
                        const name = feature.properties.name;
                        let record = countryDataMap[name];
                        if (!record && name === 'Brunei') record = countryDataMap['Brunei'];
                        
                        if (record) {
                            const curMonth = record.may;
                            const prevMonth = record.apr;
                            
                            let indicatorHTML = '';
                            if (prevMonth > 0) {
                                const growth = (((curMonth - prevMonth) / prevMonth) * 100).toFixed(1);
                                if (curMonth > prevMonth) {
                                    indicatorHTML = `<span class="text-green-600 flex items-center"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="18 15 12 9 6 15"></polyline></svg> ${growth}% increase</span>`;
                                } else if (curMonth < prevMonth) {
                                    indicatorHTML = `<span class="text-red-600 flex items-center"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg> ${Math.abs(growth)}% decrease</span>`;
                                } else {
                                    indicatorHTML = `<span class="text-slate-500">No change</span>`;
                                }
                            } else {
                                indicatorHTML = `<span class="text-slate-500 text-xs italic">No previous month available.</span>`;
                            }

                            const iso = feature.id.toLowerCase();
                            
                            const tooltipContent = `
                                <div class="p-3 min-w-[200px]">
                                    <div class="font-bold border-b pb-1 mb-2 flex items-center gap-2">
                                        <img src="https://flagcdn.com/24x18/${iso.substring(0,2)}.png" onerror="this.style.display='none'" class="w-6 shadow-sm border border-slate-200">
                                        ${name}
                                    </div>
                                    <div class="text-sm grid grid-cols-2 gap-y-1">
                                        <span class="text-slate-500">Current Month:</span> <span class="font-semibold text-right">${curMonth.toLocaleString()}</span>
                                        <span class="text-slate-500">Prev Month:</span> <span class="font-semibold text-right">${prevMonth.toLocaleString()}</span>
                                    </div>
                                    <div class="mt-2 pt-2 border-t text-sm font-medium">
                                        ${indicatorHTML}
                                    </div>
                                </div>
                            `;
                            
                            layer.bindTooltip(tooltipContent, {
                                sticky: true,
                                className: 'leaflet-tooltip-custom'
                            });

                            layer.on({
                                mouseover: function (e) {
                                    const l = e.target;
                                    
                                    // Bikin array warna-warna cerah buat highlight
                                    const brightColors = ['#FDE047', '#67E8F9', '#F9A8D4', '#FCD34D', '#86EFAC', '#C4B5FD', '#FCA5A5', '#A7F3D0'];
                                    // Pilih warna unik berdasarkan nama negaranya biar konsisten
                                    let sum = 0;
                                    for(let i = 0; i < name.length; i++) sum += name.charCodeAt(i);
                                    const hoverColor = brightColors[sum % brightColors.length];
                                    
                                    l.setStyle({ fillColor: hoverColor, fillOpacity: 1 });
                                    if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) { l.bringToFront(); }
                                },
                                mouseout: function (e) {
                                    // Pakai variabel geojsonLayer untuk mereset style dengan benar
                                    geojsonLayer.resetStyle(e.target);
                                }
                            });
                        }
                    }
                }).addTo(map);

                // Auto-zoom ke area negara-negara yang ada di dataset
                const datasetBounds = [];
                geojsonLayer.eachLayer(function(layer) {
                    const name = layer.feature.properties.name;
                    let record = countryDataMap[name];
                    if (!record && name === 'Brunei') record = countryDataMap['Brunei'];
                    if (record) {
                        datasetBounds.push(layer.getBounds());
                    }
                });
                if (datasetBounds.length > 0) {
                    let combined = datasetBounds[0];
                    datasetBounds.forEach(b => combined = combined.extend(b));
                    map.fitBounds(combined, { padding: [30, 30], maxZoom: 5 });
                }
            });

        // AI Insight Generation
        const btnGen = document.getElementById('btnGenerateInsight');
        const panel = document.getElementById('aiInsightPanel');
        const content = document.getElementById('aiInsightContent');
        const loading = document.getElementById('aiInsightLoading');
        const btnClose = document.getElementById('btnCloseInsight');

        btnClose.addEventListener('click', () => {
            panel.classList.add('hidden');
        });

        btnGen.addEventListener('click', async () => {
            panel.classList.remove('hidden');
            content.innerHTML = '';
            loading.classList.remove('hidden');
            btnGen.disabled = true;
            btnGen.classList.add('opacity-50');

            const prompt = `Generate a formal Executive Insight Report for the BSKLN Tourism Dashboard based on the dataset. 
            Format exactly as requested:
            - Executive Summary
            - Key Findings
            - Top Country
            - Lowest Country
            - Monthly Trend Analysis
            - Strategic Recommendation
            Use professional, strategic language.`;

            try {
                const response = await fetch('/ajax/chat', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ prompt: prompt, messages: [] })
                });

                const data = await response.json();
                if (!response.ok) throw new Error(data.error);
                
                content.innerHTML = marked.parse(data.text);
            } catch (err) {
                content.innerHTML = `<p class="text-red-500">Error generating insight: ${err.message}</p>`;
            } finally {
                loading.classList.add('hidden');
                btnGen.disabled = false;
                btnGen.classList.remove('opacity-50');
            }
        });
    });

    // CRUD Logic
    function openModal() {
        document.getElementById('crudForm').reset();
        document.getElementById('recordId').value = '';
        document.getElementById('modalTitle').innerText = 'Add New Record';
        document.getElementById('crudModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('crudModal').classList.add('hidden');
    }

    async function editRow(id) {
        try {
            const res = await fetch(`/ajax/visitors/${id}`);
            if (!res.ok) throw new Error('Failed to fetch record');
            const data = await res.json();
            
            document.getElementById('recordId').value = data.id;
            document.getElementById('country_name').value = data.country_name;
            document.getElementById('jan').value = data.jan;
            document.getElementById('feb').value = data.feb;
            document.getElementById('mar').value = data.mar;
            document.getElementById('apr').value = data.apr;
            document.getElementById('may').value = data.may;
            
            document.getElementById('modalTitle').innerText = 'Edit Record';
            document.getElementById('crudModal').classList.remove('hidden');
        } catch (e) {
            alert(e.message);
        }
    }
    
    async function deleteRow(id) {
        if(!confirm('Are you sure you want to delete record ID: ' + id + '?')) return;
        
        try {
            const res = await fetch(`/ajax/visitors/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            if (!res.ok) throw new Error('Failed to delete');
            $('#visitorTable').DataTable().ajax.reload();
        } catch (e) {
            alert(e.message);
        }
    }

    document.getElementById('crudForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const id = document.getElementById('recordId').value;
        const payload = {
            country_name: document.getElementById('country_name').value,
            jan: document.getElementById('jan').value,
            feb: document.getElementById('feb').value,
            mar: document.getElementById('mar').value,
            apr: document.getElementById('apr').value,
            may: document.getElementById('may').value
        };

        const url = id ? `/ajax/visitors/${id}` : '/ajax/visitors';
        const method = id ? 'PUT' : 'POST';

        try {
            const res = await fetch(url, {
                method: method,
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(payload)
            });

            if (!res.ok) throw new Error('Validation or server error');
            closeModal();
            $('#visitorTable').DataTable().ajax.reload();
        } catch (e) {
            alert(e.message);
        }
    });
</script>
@endpush
