import re
import os

files = [
    ('resources/views/customer/profile.blade.php', 'app/Http/Controllers/CustomerDashboardController.php'),
    ('resources/views/merchant/profile.blade.php', 'app/Http/Controllers/MerchantDashboardController.php'),
    ('resources/views/admin/profile.blade.php', 'app/Http/Controllers/Admin/ProfileController.php')
]

for view, ctrl in files:
    print('---', view)
    try:
        with open(view, 'r') as f:
            vcontent = f.read()
        names = re.findall(r'name=["\']([^"\']+)["\']', vcontent)
        print('Inputs in view:', set(names))
    except Exception as e:
        print(e)
