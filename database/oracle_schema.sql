   SET SERVEROUTPUT ON;



drop table reports;
drop table expenses;
drop table payroll;
drop table invoice_items;
drop table invoices;
drop table clients;
drop table transactions;
drop table employees;
drop table users;

create table users (
   id         number(19) primary key,
   name       varchar2(100) not null,
   email      varchar2(150) unique not null,
   password   varchar2(255) not null,
   role       varchar2(20) not null,
   is_active  number(1) default 1 not null,
   created_at timestamp default current_timestamp not null,
   updated_at timestamp default current_timestamp not null,
   constraint chk_user_role check ( role in ( 'admin', 'accountant', 'manager', 'employee' ) ),
   constraint chk_user_active check ( is_active in ( 0, 1 ) )
);

create table employees (
   id          number(19) primary key,
   user_id     number(19) not null,
   department  varchar2(100) not null,
   position    varchar2(100) not null,
   base_salary number(12,2) not null,
   hire_date   date not null,
   status      varchar2(20) not null,
   constraint fk_emp_user foreign key ( user_id ) references users ( id ) on delete cascade,
   constraint chk_emp_status check ( status in ( 'active', 'inactive', 'terminated' ) )
);

create table transactions (
   id               number(19) primary key,
   user_id          number(19) not null,
   type             varchar2(20) not null,
   amount           number(15,2) not null,
   category         varchar2(100),
   reference_no     varchar2(50) unique not null,
   description      varchar2(4000),
   transaction_date date not null,
   constraint fk_tx_user foreign key ( user_id ) references users ( id ) on delete cascade,
   constraint chk_tx_type check ( type in ( 'income', 'expense', 'transfer' ) )
);

create table clients (
   id         number(19) primary key,
   name       varchar2(150) not null,
   email      varchar2(150) unique not null,
   phone      varchar2(20),
   address    varchar2(4000),
   tax_id     varchar2(50),
   created_at timestamp default current_timestamp not null
);

create table invoices (
   id         number(19) primary key,
   invoice_no varchar2(30) unique not null,
   user_id    number(19) not null,
   client_id  number(19) not null,
   subtotal   number(15,2) not null,
   tax_rate   number(5,2) default 0 not null,
   total      number(15,2) not null,
   status     varchar2(20) not null,
   issue_date date,
   due_date   date,
   constraint fk_inv_user foreign key ( user_id ) references users ( id ) on delete cascade,
   constraint fk_inv_client foreign key ( client_id ) references clients ( id ) on delete cascade,
   constraint chk_inv_status check ( status in ( 'draft', 'sent', 'paid', 'overdue', 'cancelled' ) )
);

create table invoice_items (
   id          number(19) primary key,
   invoice_id  number(19) not null,
   description varchar2(255) not null,
   quantity    number(10,2) not null,
   unit_price  number(12,2) not null,
   total       number(15,2) generated always as ( quantity * unit_price ),
   constraint fk_item_invoice foreign key ( invoice_id ) references invoices ( id ) on delete cascade
);

create table payroll (
   id               number(19) primary key,
   employee_id      number(19) not null,
   month            number(2) not null,
   year             number(4) not null,
   gross_salary     number(12,2) not null,
   tax_deduction    number(10,2) default 0 not null,
   other_deductions number(10,2) default 0 not null,
   net_salary       number(12,2) generated always as ( gross_salary - tax_deduction - other_deductions ),
   payment_date     date,
   status           varchar2(20) not null,
   constraint fk_pay_employee foreign key ( employee_id ) references employees ( id ) on delete cascade,
   constraint chk_pay_month check ( month between 1 and 12 ),
   constraint chk_pay_status check ( status in ( 'pending', 'paid', 'cancelled' ) )
);

create table expenses (
   id           number(19) primary key,
   user_id      number(19) not null,
   category     varchar2(100) not null,
   amount       number(12,2) not null,
   vendor       varchar2(150),
   receipt_path varchar2(255),
   expense_date date not null,
   approved_by  number(19),
   status       varchar2(20) not null,
   constraint fk_exp_user foreign key ( user_id ) references users ( id ) on delete cascade,
   constraint fk_exp_approver foreign key ( approved_by ) references users ( id ) on delete set null,
   constraint chk_exp_status check ( status in ( 'pending', 'approved', 'rejected' ) )
);

create table reports (
   id           number(19) primary key,
   user_id      number(19) not null,
   report_type  varchar2(80) not null,
   period_start date,
   period_end   date,
   data         clob,
   generated_at timestamp default current_timestamp not null,
   constraint fk_rep_user foreign key ( user_id ) references users ( id ) on delete cascade
);