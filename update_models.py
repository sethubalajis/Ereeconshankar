import os
import re

models_dir = r"d:\e-reckon-erp\app\Models"

models_content = {
    "Company.php": """
    protected $guarded = [];

    public function branches() {
        return $this->hasMany(Branch::class);
    }
    public function businessUnits() {
        return $this->hasMany(BusinessUnit::class);
    }
""",
    "Branch.php": """
    protected $guarded = [];

    public function company() {
        return $this->belongsTo(Company::class);
    }
    public function city() {
        return $this->belongsTo(City::class);
    }
    public function state() {
        return $this->belongsTo(State::class);
    }
    public function country() {
        return $this->belongsTo(Country::class);
    }
    public function departments() {
        return $this->hasMany(Department::class);
    }
    public function costCenters() {
        return $this->hasMany(CostCenter::class);
    }
""",
    "BusinessUnit.php": """
    protected $guarded = [];

    public function company() {
        return $this->belongsTo(Company::class);
    }
""",
    "Department.php": """
    protected $guarded = [];

    public function branch() {
        return $this->belongsTo(Branch::class);
    }
""",
    "CostCenter.php": """
    protected $guarded = [];

    public function branch() {
        return $this->belongsTo(Branch::class);
    }
""",
    "FinancialYear.php": """
    protected $guarded = [];
""",
    "Currency.php": """
    protected $guarded = [];
""",
    "Country.php": """
    protected $guarded = [];

    public function states() {
        return $this->hasMany(State::class);
    }
""",
    "State.php": """
    protected $guarded = [];

    public function country() {
        return $this->belongsTo(Country::class);
    }
    public function cities() {
        return $this->hasMany(City::class);
    }
""",
    "City.php": """
    protected $guarded = [];

    public function state() {
        return $this->belongsTo(State::class);
    }
""",
    "Role.php": """
    protected $guarded = [];

    public function permissions() {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }
    public function users() {
        return $this->belongsToMany(User::class, 'user_roles');
    }
""",
    "Permission.php": """
    protected $guarded = [];

    public function roles() {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }
""",
    "UserRole.php": """
    protected $guarded = [];
""",
    "RolePermission.php": """
    protected $guarded = [];
""",
    "UserBranchAccess.php": """
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function branch() {
        return $this->belongsTo(Branch::class);
    }
""",
    "LoginHistory.php": """
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }
""",
    "User.php": """
    public function roles() {
        return $this->belongsToMany(Role::class, 'user_roles');
    }
    public function branchAccesses() {
        return $this->hasMany(UserBranchAccess::class);
    }
    public function loginHistories() {
        return $this->hasMany(LoginHistory::class);
    }
"""
}

for filename, content in models_content.items():
    filepath = os.path.join(models_dir, filename)
    if not os.path.exists(filepath):
        print(f"Skipping {filename}, not found.")
        continue
        
    with open(filepath, 'r') as f:
        original = f.read()
        
    if filename == "User.php":
        # Insert before last brace
        if "public function roles()" not in original:
            original = original.rsplit('}', 1)[0] + content + "\n}"
            with open(filepath, 'w') as f:
                f.write(original)
            print(f"Updated {filename}")
    else:
        # Replace the inside of the class
        # Look for class Name extends Model \n{
        pattern = re.compile(r'(class\s+\w+\s+extends\s+Model\s*\{)', re.DOTALL)
        if pattern.search(original):
            if "protected $guarded" not in original:
                new_original = pattern.sub(r'\1\n' + content, original)
                with open(filepath, 'w') as f:
                    f.write(new_original)
                print(f"Updated {filename}")
        else:
            print(f"Could not find class definition in {filename}")

# Also delete Session model if exists
session_model = os.path.join(models_dir, "Session.php")
if os.path.exists(session_model):
    os.remove(session_model)
    print("Deleted Session.php")
