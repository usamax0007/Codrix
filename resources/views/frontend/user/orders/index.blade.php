@extends('frontend.user.layout.app')

@section('content')
    <div class="flex-1 lg:ml-64">
        <main class="p-6">
            <h1 class="text-2xl font-bold text-white mb-6">My Orders</h1>

            <div class="bg-gray-800 rounded-lg overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Order ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        <tr class="hover:bg-gray-750">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-300">#ORD-001</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-300">Jan 15, 2026</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full bg-green-900 text-green-300">
                                    Completed
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-300">$1,250.00</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button class="text-blue-400 hover:text-blue-300 text-sm">View Details</button>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-750">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-300">#ORD-002</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-300">Jan 20, 2026</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-900 text-yellow-300">
                                    Processing
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-300">$850.00</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button class="text-blue-400 hover:text-blue-300 text-sm">View Details</button>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-750">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-300">#ORD-003</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-300">Feb 1, 2026</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full bg-blue-900 text-blue-300">
                                    Pending
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-300">$2,100.00</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button class="text-blue-400 hover:text-blue-300 text-sm">View Details</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-6 text-center text-gray-400 text-sm">
                <p>No more orders to display</p>
            </div>
        </main>
    </div>
@endsection
