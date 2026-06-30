import os
import re
import glob

migrations_dir = r"d:\e-reckon-erp\database\migrations"

schemas = {
    # GST & Taxation
    "create_gst_rates_table.php": """$table->id();
            $table->decimal('cgst_percentage', 5, 2)->default(0);
            $table->decimal('sgst_percentage', 5, 2)->default(0);
            $table->decimal('igst_percentage', 5, 2)->default(0);
            $table->decimal('cess_percentage', 5, 2)->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();""",
    "create_gst_categories_table.php": """$table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->boolean('status')->default(true);
            $table->timestamps();""",
    "create_gst_returns_table.php": """$table->id();
            $table->string('return_type'); // GSTR1, GSTR3B
            $table->date('period_start');
            $table->date('period_end');
            $table->date('filing_date')->nullable();
            $table->string('arn_number')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();""",
    "create_tax_invoices_table.php": """$table->id();
            $table->unsignedBigInteger('invoiceable_id');
            $table->string('invoiceable_type');
            $table->string('invoice_number')->unique();
            $table->date('invoice_date');
            $table->decimal('taxable_amount', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->timestamps();""",
    "create_e_invoices_table.php": """$table->id();
            $table->foreignId('tax_invoice_id')->constrained('tax_invoices');
            $table->string('irn')->unique();
            $table->string('ack_no')->nullable();
            $table->date('ack_date')->nullable();
            $table->string('status')->default('generated');
            $table->timestamps();""",
    "create_e_way_bills_table.php": """$table->id();
            $table->foreignId('tax_invoice_id')->constrained('tax_invoices');
            $table->string('eway_bill_no')->unique();
            $table->date('valid_until')->nullable();
            $table->string('transporter_id')->nullable();
            $table->string('vehicle_no')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();""",
    "create_tds_records_table.php": """$table->id();
            $table->string('section_code');
            $table->decimal('tds_percentage', 5, 2)->default(0);
            $table->unsignedBigInteger('deductee_id');
            $table->string('deductee_type');
            $table->decimal('amount', 15, 2)->default(0);
            $table->timestamps();""",
    "create_tcs_records_table.php": """$table->id();
            $table->string('section_code');
            $table->decimal('tcs_percentage', 5, 2)->default(0);
            $table->unsignedBigInteger('collectee_id');
            $table->string('collectee_type');
            $table->decimal('amount', 15, 2)->default(0);
            $table->timestamps();""",
    "create_hsn_sac_codes_table.php": """$table->id();
            $table->string('code')->unique();
            $table->string('type')->default('HSN'); // HSN or SAC
            $table->text('description')->nullable();
            $table->timestamps();""",

    # Human Resources
    "create_designations_table.php": """$table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();""",
    "create_employees_table.php": """$table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('department_id')->nullable()->constrained('departments');
            $table->foreignId('designation_id')->nullable()->constrained('designations');
            $table->foreignId('branch_id')->nullable()->constrained('branches');
            $table->string('employee_code')->unique();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->date('dob')->nullable();
            $table->date('joining_date')->nullable();
            $table->string('pan_number')->nullable();
            $table->string('pf_number')->nullable();
            $table->string('esi_number')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();""",
    "create_employee_documents_table.php": """$table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('document_type'); // ID, Address, Resume
            $table->string('document_path');
            $table->boolean('is_verified')->default(false);
            $table->timestamps();""",
    "create_attendances_table.php": """$table->id();
            $table->foreignId('employee_id')->constrained('employees');
            $table->date('date');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->string('status')->default('present'); // present, absent, half_day
            $table->timestamps();""",
    "create_shifts_table.php": """$table->id();
            $table->string('name');
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('status')->default(true);
            $table->timestamps();""",
    "create_leave_types_table.php": """$table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->integer('days_allowed')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();""",
    "create_employee_leaves_table.php": """$table->id();
            $table->foreignId('employee_id')->constrained('employees');
            $table->foreignId('leave_type_id')->constrained('leave_types');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status')->default('pending');
            $table->text('reason')->nullable();
            $table->timestamps();""",
    "create_holidays_table.php": """$table->id();
            $table->string('name');
            $table->date('date');
            $table->text('description')->nullable();
            $table->boolean('is_restricted')->default(false);
            $table->timestamps();""",
    "create_employee_transfers_table.php": """$table->id();
            $table->foreignId('employee_id')->constrained('employees');
            $table->foreignId('from_branch_id')->constrained('branches');
            $table->foreignId('to_branch_id')->constrained('branches');
            $table->date('transfer_date');
            $table->string('status')->default('pending');
            $table->timestamps();""",
    "create_employee_exits_table.php": """$table->id();
            $table->foreignId('employee_id')->constrained('employees');
            $table->date('resignation_date');
            $table->date('last_working_day')->nullable();
            $table->string('exit_type')->nullable(); // Resigned, Terminated
            $table->text('reason')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();""",

    # Payroll
    "create_salary_structures_table.php": """$table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->decimal('basic_percentage', 5, 2)->default(0);
            $table->decimal('hra_percentage', 5, 2)->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();""",
    "create_salary_components_table.php": """$table->id();
            $table->string('name');
            $table->string('type'); // earning, deduction
            $table->boolean('is_taxable')->default(true);
            $table->boolean('status')->default(true);
            $table->timestamps();""",
    "create_employee_salary_assignments_table.php": """$table->id();
            $table->foreignId('employee_id')->constrained('employees');
            $table->foreignId('salary_structure_id')->constrained('salary_structures');
            $table->decimal('gross_salary', 15, 2)->default(0);
            $table->date('effective_from');
            $table->timestamps();""",
    "create_payroll_cycles_table.php": """$table->id();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->date('payment_date')->nullable();
            $table->string('status')->default('draft');
            $table->timestamps();""",
    "create_bonuses_table.php": """$table->id();
            $table->foreignId('employee_id')->constrained('employees');
            $table->string('bonus_type');
            $table->decimal('amount', 15, 2)->default(0);
            $table->date('payout_date')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();""",
    "create_incentives_table.php": """$table->id();
            $table->foreignId('employee_id')->constrained('employees');
            $table->date('month');
            $table->decimal('amount', 15, 2)->default(0);
            $table->text('remarks')->nullable();
            $table->timestamps();""",
    "create_reimbursements_table.php": """$table->id();
            $table->foreignId('employee_id')->constrained('employees');
            $table->date('expense_date');
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('receipt_path')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();""",
    "create_deductions_table.php": """$table->id();
            $table->foreignId('employee_id')->constrained('employees');
            $table->string('deduction_type');
            $table->decimal('amount', 15, 2)->default(0);
            $table->date('month');
            $table->timestamps();""",
    "create_pf_contributions_table.php": """$table->id();
            $table->foreignId('employee_id')->constrained('employees');
            $table->decimal('employee_share', 15, 2)->default(0);
            $table->decimal('employer_share', 15, 2)->default(0);
            $table->date('month');
            $table->timestamps();""",
    "create_esi_contributions_table.php": """$table->id();
            $table->foreignId('employee_id')->constrained('employees');
            $table->decimal('employee_share', 15, 2)->default(0);
            $table->decimal('employer_share', 15, 2)->default(0);
            $table->date('month');
            $table->timestamps();""",
    "create_professional_taxes_table.php": """$table->id();
            $table->foreignId('employee_id')->constrained('employees');
            $table->foreignId('state_id')->nullable()->constrained('states');
            $table->decimal('amount', 15, 2)->default(0);
            $table->date('month');
            $table->timestamps();""",
    "create_payroll_processings_table.php": """$table->id();
            $table->foreignId('payroll_cycle_id')->constrained('payroll_cycles');
            $table->foreignId('employee_id')->constrained('employees');
            $table->decimal('gross_pay', 15, 2)->default(0);
            $table->decimal('total_deductions', 15, 2)->default(0);
            $table->decimal('net_pay', 15, 2)->default(0);
            $table->string('status')->default('processed');
            $table->timestamps();""",
    "create_payslips_table.php": """$table->id();
            $table->foreignId('payroll_processing_id')->constrained('payroll_processings');
            $table->string('payslip_number')->unique();
            $table->string('document_path')->nullable();
            $table->timestamps();""",

    # Asset Management
    "create_asset_categories_table.php": """$table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->boolean('status')->default(true);
            $table->timestamps();""",
    "create_assets_table.php": """$table->id();
            $table->foreignId('asset_category_id')->constrained('asset_categories');
            $table->foreignId('branch_id')->constrained('branches');
            $table->string('name');
            $table->string('asset_code')->unique();
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_cost', 15, 2)->default(0);
            $table->string('status')->default('active'); // active, retired, maintenance
            $table->timestamps();""",
    "create_asset_allocations_table.php": """$table->id();
            $table->foreignId('asset_id')->constrained('assets');
            $table->foreignId('employee_id')->constrained('employees');
            $table->date('allocation_date');
            $table->date('return_date')->nullable();
            $table->string('status')->default('allocated');
            $table->timestamps();""",
    "create_asset_transfers_table.php": """$table->id();
            $table->foreignId('asset_id')->constrained('assets');
            $table->foreignId('from_branch_id')->constrained('branches');
            $table->foreignId('to_branch_id')->constrained('branches');
            $table->date('transfer_date');
            $table->string('status')->default('pending');
            $table->timestamps();""",
    "create_asset_maintenances_table.php": """$table->id();
            $table->foreignId('asset_id')->constrained('assets');
            $table->date('maintenance_date');
            $table->decimal('cost', 15, 2)->default(0);
            $table->text('description')->nullable();
            $table->timestamps();""",
    "create_asset_depreciations_table.php": """$table->id();
            $table->foreignId('asset_id')->constrained('assets');
            $table->date('depreciation_date');
            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('current_value', 15, 2)->default(0);
            $table->timestamps();""",
    "create_asset_disposals_table.php": """$table->id();
            $table->foreignId('asset_id')->constrained('assets');
            $table->date('disposal_date');
            $table->decimal('sale_value', 15, 2)->default(0);
            $table->text('reason')->nullable();
            $table->timestamps();""",

    # Service & Complaints
    "create_complaint_categories_table.php": """$table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->boolean('status')->default(true);
            $table->timestamps();""",
    "create_service_requests_table.php": """$table->id();
            $table->foreignId('customer_id')->constrained('customers');
            $table->string('request_number')->unique();
            $table->date('request_date');
            $table->text('issue_description')->nullable();
            $table->string('status')->default('open');
            $table->timestamps();""",
    "create_complaint_tickets_table.php": """$table->id();
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('complaint_category_id')->constrained('complaint_categories');
            $table->string('ticket_number')->unique();
            $table->text('description')->nullable();
            $table->string('status')->default('open');
            $table->timestamps();""",
    "create_warranty_claims_table.php": """$table->id();
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('product_id')->constrained('products');
            $table->string('claim_number')->unique();
            $table->date('claim_date');
            $table->string('status')->default('pending');
            $table->timestamps();""",
    "create_service_assignments_table.php": """$table->id();
            $table->foreignId('service_request_id')->constrained('service_requests');
            $table->foreignId('employee_id')->constrained('employees');
            $table->date('assigned_date');
            $table->string('status')->default('assigned');
            $table->timestamps();""",
    "create_technician_visits_table.php": """$table->id();
            $table->foreignId('service_assignment_id')->constrained('service_assignments');
            $table->date('visit_date');
            $table->text('remarks')->nullable();
            $table->timestamps();""",
    "create_service_reports_table.php": """$table->id();
            $table->foreignId('service_request_id')->constrained('service_requests');
            $table->text('resolution_details')->nullable();
            $table->date('report_date');
            $table->timestamps();""",

    # Reports & BI Dashboard
    "create_dashboard_widgets_table.php": """$table->id();
            $table->string('name');
            $table->string('type'); // chart, table, metric
            $table->text('config')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();""",
    "create_sales_reports_table.php": """$table->id();
            $table->foreignId('branch_id')->constrained('branches');
            $table->date('report_date');
            $table->decimal('total_sales', 15, 2)->default(0);
            $table->timestamps();""",
    "create_purchase_reports_table.php": """$table->id();
            $table->foreignId('branch_id')->constrained('branches');
            $table->date('report_date');
            $table->decimal('total_purchases', 15, 2)->default(0);
            $table->timestamps();""",
    "create_inventory_reports_table.php": """$table->id();
            $table->foreignId('branch_id')->constrained('branches');
            $table->date('report_date');
            $table->decimal('total_inventory_value', 15, 2)->default(0);
            $table->timestamps();""",
    "create_gst_reports_table.php": """$table->id();
            $table->string('report_type'); // GSTR1, GSTR3B
            $table->date('period_start');
            $table->date('period_end');
            $table->text('summary_data')->nullable();
            $table->timestamps();""",
    "create_financial_reports_table.php": """$table->id();
            $table->string('report_type'); // PnL, BalanceSheet
            $table->date('as_of_date');
            $table->text('data')->nullable();
            $table->timestamps();""",
    "create_hr_reports_table.php": """$table->id();
            $table->date('report_month');
            $table->integer('total_employees')->default(0);
            $table->integer('new_hires')->default(0);
            $table->integer('exits')->default(0);
            $table->timestamps();""",
    "create_branch_performance_reports_table.php": """$table->id();
            $table->foreignId('branch_id')->constrained('branches');
            $table->date('report_month');
            $table->decimal('score', 5, 2)->default(0);
            $table->timestamps();""",
    "create_kpi_reports_table.php": """$table->id();
            $table->string('kpi_name');
            $table->date('report_date');
            $table->decimal('value', 15, 2)->default(0);
            $table->timestamps();"""
}

updated = []
for filepath in glob.glob(os.path.join(migrations_dir, "*.php")):
    filename = os.path.basename(filepath)
    matched_schema = None
    for key in schemas.keys():
        if filename.endswith(key):
            matched_schema = key
            break
            
    if matched_schema:
        with open(filepath, 'r') as f:
            content = f.read()
            
        pattern = re.compile(r'(\$table->id\(\);).*?(\$table->timestamps\(\);)', re.DOTALL)
        if pattern.search(content):
            new_content = pattern.sub(lambda m: schemas[matched_schema], content)
            with open(filepath, 'w') as f:
                f.write(new_content)
            updated.append(filename)

print(f"Updated {len(updated)} migrations.")
