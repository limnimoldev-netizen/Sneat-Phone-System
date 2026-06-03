@extends('layouts.app')
@push('styles')
@endpush

@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Sale</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f8f9fa] min-h-screen p-6 font-sans">

    <div class="max-w-7xl mx-auto bg-white rounded-lg shadow-sm border border-gray-100 p-6">
        <h2 class="text-xl font-medium text-gray-500 mb-6">Register Sale</h2>

        <form id="saleForm" onsubmit="handleSubmit(event)">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Sale Date</label>
                    <div class="relative">
                        <input type="date" required
                            class="w-full border border-gray-200 rounded px-3 py-2 text-gray-700 focus:outline-none focus:border-indigo-500 appearance-none bg-transparent">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Customer</label>
                    <select class="w-full border border-gray-200 rounded px-3 py-2 text-gray-700 focus:outline-none focus:border-indigo-500 bg-white">
                        <option>Walk in Customer</option>
                        <option>John Doe</option>
                        <option>Jane Smith</option>
                    </select>
                </div>
            </div>

            <div class="mb-8">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Product Name</label>
                <select id="productSelect" onchange="addProduct(this)"
                    class="w-full border border-gray-200 rounded px-3 py-2 text-gray-700 focus:outline-none focus:border-indigo-500 bg-white">
                    <option value="" disabled selected>Select Order Product</option>
                    <option value="1" data-imei="IMEI847294" data-name="iPhone 15 Pro" data-detail="128GB, Natural Titanium" data-price="999.00">iPhone 15 Pro</option>
                    <option value="2" data-imei="IMEI392019" data-name="Samsung Galaxy S24" data-detail="256GB, Onyx Black" data-price="799.00">Samsung Galaxy S24</option>
                    <option value="3" data-imei="IMEI583021" data-name="iPad Air" data-detail="64GB, Wi-Fi, Space Gray" data-price="599.00">iPad Air</option>
                </select>
            </div>

            <div class="border border-gray-100 rounded mb-8 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/50">
                            <th class="p-3 text-xs font-bold text-gray-400 uppercase tracking-wider w-1/5">Product IMEI</th>
                            <th class="p-3 text-xs font-bold text-gray-400 uppercase tracking-wider w-1/5">Product Name</th>
                            <th class="p-3 text-xs font-bold text-gray-400 uppercase tracking-wider w-2/5">Product Detail</th>
                            <th class="p-3 text-xs font-bold text-gray-400 uppercase tracking-wider w-1/10">Price ($)</th>
                            <th class="p-3 text-xs font-bold text-gray-400 uppercase tracking-wider w-1/10 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr id="emptyRow">
                            <td colspan="5" class="py-12 text-center text-xs font-bold text-gray-400 tracking-wider bg-white">
                                NO DATA AVAILABLE
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end items-center gap-2 mb-8 pr-12">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total :</span>
                <span id="totalAmount" class="text-sm font-semibold text-gray-700">0.00</span>
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Note</label>
                <textarea rows="4" 
                    class="w-full border border-gray-200 rounded p-3 text-gray-700 focus:outline-none focus:border-indigo-500 resize-y"></textarea>
            </div>

            <div class="flex gap-3">
                <button type="submit" 
                    class="bg-[#6366f1] hover:bg-[#4f46e5] text-white text-sm font-medium px-4 py-2 rounded shadow-sm transition-colors cursor-pointer">
                    Submit Order
                </button>
                <button type="button" onclick="resetForm()"
                    class="bg-white border border-gray-200 hover:bg-gray-50 text-gray-500 text-sm font-medium px-4 py-2 rounded shadow-sm transition-colors cursor-pointer">
                    Cancel
                </button>
            </div>
        </form>
    </div>

    <script>
        let items = [];

        function addProduct(selectElement) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            if (!selectedOption.value) return;

            // Extract data attributes
            const imei = selectedOption.getAttribute('data-imei');
            const name = selectedOption.getAttribute('data-name');
            const detail = selectedOption.getAttribute('data-detail');
            const price = parseFloat(selectedOption.getAttribute('data-price'));

            // Add item to array
            items.push({ id: Date.now(), imei, name, detail, price });
            
            // Reset select element back to placeholder
            selectElement.selectedIndex = 0;

            renderTable();
        }

        function removeItem(id) {
            items = items.filter(item => item.id !== id);
            renderTable();
        }

        function renderTable() {
            const tbody = document.getElementById('tableBody');
            const totalAmountSpan = document.getElementById('totalAmount');
            
            // Clear existing elements dynamically
            tbody.innerHTML = '';

            if (items.length === 0) {
                tbody.innerHTML = `
                    <tr id="emptyRow">
                        <td colspan="5" class="py-12 text-center text-xs font-bold text-gray-400 tracking-wider bg-white">
                            NO DATA AVAILABLE
                        </td>
                    </tr>`;
                totalAmountSpan.textContent = '0.00';
                return;
            }

            let total = 0;

            items.forEach(item => {
                total += item.price;
                const row = document.createElement('tr');
                row.className = "border-b border-gray-100 hover:bg-gray-50/50 transition-colors text-sm text-gray-600";
                row.innerHTML = `
                    <td class="p-3 font-medium">${item.imei}</td>
                    <td class="p-3">${item.name}</td>
                    <td class="p-3 text-gray-400">${item.detail}</td>
                    <td class="p-3 font-medium">${item.price.toFixed(2)}</td>
                    <td class="p-3 text-center">
                        <button type="button" onclick="removeItem(${item.id})" class="text-red-500 hover:text-red-700 p-1">
                            <i class="fa-regular fa-trash-can"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });

            totalAmountSpan.textContent = total.toFixed(2);
        }

        function resetForm() {
            document.getElementById('saleForm').reset();
            items = [];
            renderTable();
        }

        function handleSubmit(event) {
            event.preventDefault();
            if(items.length === 0) {
                alert('Please add at least one product to the order.');
                return;
            }
            alert('Order submitted successfully!');
            resetForm();
        }
    </script>
</body>
</html>


@endsection
