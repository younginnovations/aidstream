--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: activities_in_registry; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE activities_in_registry (
    id integer NOT NULL,
    organization_id integer NOT NULL,
    activity_id integer NOT NULL,
    activity_data json NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.activities_in_registry OWNER TO postgres;

--
-- Name: activities_in_registry_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE activities_in_registry_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.activities_in_registry_id_seq OWNER TO postgres;

--
-- Name: activities_in_registry_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE activities_in_registry_id_seq OWNED BY activities_in_registry.id;


--
-- Name: activity_data; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE activity_data (
    id integer NOT NULL,
    identifier json NOT NULL,
    other_identifier json,
    title json,
    description json,
    activity_status integer,
    activity_date json,
    contact_info json,
    activity_scope json,
    participating_organization json,
    recipient_country json,
    recipient_region json,
    location json,
    sector json,
    country_budget_items json,
    humanitarian_scope json,
    policy_marker json,
    collaboration_type json,
    default_flow_type json,
    default_finance_type json,
    default_aid_type json,
    default_tied_status json,
    budget json,
    planned_disbursement json,
    capital_spend json,
    document_link json,
    related_activity json,
    legacy_data json,
    conditions json,
    activity_workflow integer DEFAULT 0 NOT NULL,
    organization_id integer NOT NULL,
    default_field_values json,
    published_to_registry integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.activity_data OWNER TO postgres;

--
-- Name: activity_data_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE activity_data_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.activity_data_id_seq OWNER TO postgres;

--
-- Name: activity_data_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE activity_data_id_seq OWNED BY activity_data.id;


--
-- Name: activity_document_links; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE activity_document_links (
    id integer NOT NULL,
    activity_id integer NOT NULL,
    document_link json NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.activity_document_links OWNER TO postgres;

--
-- Name: activity_document_links_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE activity_document_links_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.activity_document_links_id_seq OWNER TO postgres;

--
-- Name: activity_document_links_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE activity_document_links_id_seq OWNED BY activity_document_links.id;


--
-- Name: activity_published; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE activity_published (
    id integer NOT NULL,
    published_activities json,
    filename character varying(255) NOT NULL,
    published_to_register integer DEFAULT 0 NOT NULL,
    organization_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.activity_published OWNER TO postgres;

--
-- Name: activity_published_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE activity_published_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.activity_published_id_seq OWNER TO postgres;

--
-- Name: activity_published_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE activity_published_id_seq OWNED BY activity_published.id;


--
-- Name: activity_results; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE activity_results (
    id integer NOT NULL,
    activity_id integer NOT NULL,
    result json NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.activity_results OWNER TO postgres;

--
-- Name: activity_results_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE activity_results_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.activity_results_id_seq OWNER TO postgres;

--
-- Name: activity_results_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE activity_results_id_seq OWNED BY activity_results.id;


--
-- Name: activity_transactions; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE activity_transactions (
    id integer NOT NULL,
    activity_id integer NOT NULL,
    transaction json NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.activity_transactions OWNER TO postgres;

--
-- Name: activity_transactions_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE activity_transactions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.activity_transactions_id_seq OWNER TO postgres;

--
-- Name: activity_transactions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE activity_transactions_id_seq OWNED BY activity_transactions.id;


--
-- Name: documents; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE documents (
    id integer NOT NULL,
    filename character varying(255),
    url character varying(255),
    activities json,
    org_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.documents OWNER TO postgres;

--
-- Name: documents_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE documents_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.documents_id_seq OWNER TO postgres;

--
-- Name: documents_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE documents_id_seq OWNED BY documents.id;


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE failed_jobs (
    id integer NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    failed_at timestamp(0) without time zone NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO postgres;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.failed_jobs_id_seq OWNER TO postgres;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE failed_jobs_id_seq OWNED BY failed_jobs.id;


--
-- Name: jobs; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


ALTER TABLE public.jobs OWNER TO postgres;

--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.jobs_id_seq OWNER TO postgres;

--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE jobs_id_seq OWNED BY jobs.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE migrations (
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO postgres;

--
-- Name: organization_data; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE organization_data (
    id integer NOT NULL,
    name json,
    total_budget json,
    recipient_organization_budget json,
    recipient_region_budget json,
    recipient_country_budget json,
    total_expenditure json,
    document_link json,
    organization_id integer NOT NULL,
    status integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.organization_data OWNER TO postgres;

--
-- Name: organization_data_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE organization_data_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.organization_data_id_seq OWNER TO postgres;

--
-- Name: organization_data_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE organization_data_id_seq OWNED BY organization_data.id;


--
-- Name: organization_published; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE organization_published (
    id integer NOT NULL,
    filename character varying(255) NOT NULL,
    published_to_register integer DEFAULT 0 NOT NULL,
    organization_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.organization_published OWNER TO postgres;

--
-- Name: organization_published_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE organization_published_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.organization_published_id_seq OWNER TO postgres;

--
-- Name: organization_published_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE organization_published_id_seq OWNED BY organization_published.id;


--
-- Name: organizations; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE organizations (
    id integer NOT NULL,
    user_identifier character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    address character varying(255) NOT NULL,
    telephone character varying(255),
    reporting_org json,
    country character varying(255),
    twitter character varying(255),
    disqus_comments bigint,
    logo character varying(255),
    logo_url character varying(255),
    organization_url character varying(255),
    status integer DEFAULT 1 NOT NULL,
    published_to_registry integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    display integer DEFAULT 1 NOT NULL
);


ALTER TABLE public.organizations OWNER TO postgres;

--
-- Name: organizations_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE organizations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.organizations_id_seq OWNER TO postgres;

--
-- Name: organizations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE organizations_id_seq OWNED BY organizations.id;


--
-- Name: password_resets; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE password_resets (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone NOT NULL
);


ALTER TABLE public.password_resets OWNER TO postgres;

--
-- Name: role; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE role (
    id integer NOT NULL,
    role character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.role OWNER TO postgres;

--
-- Name: role_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE role_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.role_id_seq OWNER TO postgres;

--
-- Name: role_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE role_id_seq OWNED BY role.id;


--
-- Name: settings; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE settings (
    id integer NOT NULL,
    publishing_type character varying(255) DEFAULT 'unsegmented'::character varying NOT NULL,
    registry_info json,
    default_field_values json,
    default_field_groups json,
    version character varying(16) DEFAULT '2.01'::character varying NOT NULL,
    organization_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.settings OWNER TO postgres;

--
-- Name: settings_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE settings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.settings_id_seq OWNER TO postgres;

--
-- Name: settings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE settings_id_seq OWNED BY settings.id;


--
-- Name: user_activities; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE user_activities (
    id integer NOT NULL,
    user_id integer NOT NULL,
    action character varying(255) NOT NULL,
    param json NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    data json,
    organization_id integer
);


ALTER TABLE public.user_activities OWNER TO postgres;

--
-- Name: user_activities_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE user_activities_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.user_activities_id_seq OWNER TO postgres;

--
-- Name: user_activities_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE user_activities_id_seq OWNED BY user_activities.id;


--
-- Name: user_group; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE user_group (
    id integer NOT NULL,
    group_name character varying(255) NOT NULL,
    group_identifier character varying(255) NOT NULL,
    user_id integer NOT NULL,
    assigned_organizations json NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.user_group OWNER TO postgres;

--
-- Name: user_group_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE user_group_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.user_group_id_seq OWNER TO postgres;

--
-- Name: user_group_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE user_group_id_seq OWNED BY user_group.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE users (
    id integer NOT NULL,
    first_name character varying(255) NOT NULL,
    last_name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    username character varying(255) NOT NULL,
    password character varying(60) NOT NULL,
    role_id integer,
    org_id integer,
    user_permission json,
    time_zone_id integer DEFAULT 1 NOT NULL,
    time_zone character varying(255) DEFAULT 'GMT'::character varying NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.users OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE users_id_seq OWNED BY users.id;


--
-- Name: versions; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE versions (
    id integer NOT NULL,
    version character varying(16) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.versions OWNER TO postgres;

--
-- Name: versions_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE versions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.versions_id_seq OWNER TO postgres;

--
-- Name: versions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE versions_id_seq OWNED BY versions.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY activities_in_registry ALTER COLUMN id SET DEFAULT nextval('activities_in_registry_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY activity_data ALTER COLUMN id SET DEFAULT nextval('activity_data_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY activity_document_links ALTER COLUMN id SET DEFAULT nextval('activity_document_links_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY activity_published ALTER COLUMN id SET DEFAULT nextval('activity_published_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY activity_results ALTER COLUMN id SET DEFAULT nextval('activity_results_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY activity_transactions ALTER COLUMN id SET DEFAULT nextval('activity_transactions_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY documents ALTER COLUMN id SET DEFAULT nextval('documents_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY failed_jobs ALTER COLUMN id SET DEFAULT nextval('failed_jobs_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY jobs ALTER COLUMN id SET DEFAULT nextval('jobs_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY organization_data ALTER COLUMN id SET DEFAULT nextval('organization_data_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY organization_published ALTER COLUMN id SET DEFAULT nextval('organization_published_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY organizations ALTER COLUMN id SET DEFAULT nextval('organizations_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY role ALTER COLUMN id SET DEFAULT nextval('role_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY settings ALTER COLUMN id SET DEFAULT nextval('settings_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_activities ALTER COLUMN id SET DEFAULT nextval('user_activities_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_group ALTER COLUMN id SET DEFAULT nextval('user_group_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users ALTER COLUMN id SET DEFAULT nextval('users_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY versions ALTER COLUMN id SET DEFAULT nextval('versions_id_seq'::regclass);


--
-- Data for Name: activities_in_registry; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY activities_in_registry (id, organization_id, activity_id, activity_data, created_at, updated_at) FROM stdin;
\.


--
-- Name: activities_in_registry_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('activities_in_registry_id_seq', 1, false);


--
-- Data for Name: activity_data; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY activity_data (id, identifier, other_identifier, title, description, activity_status, activity_date, contact_info, activity_scope, participating_organization, recipient_country, recipient_region, location, sector, country_budget_items, humanitarian_scope, policy_marker, collaboration_type, default_flow_type, default_finance_type, default_aid_type, default_tied_status, budget, planned_disbursement, capital_spend, document_link, related_activity, legacy_data, conditions, activity_workflow, organization_id, default_field_values, published_to_registry, created_at, updated_at) FROM stdin;
2	{"activity_identifier":"FCS\\/MG\\/2\\/13\\/465","iati_identifier_text":"-FCS\\/MG\\/2\\/13\\/465"}	[{"reference":"","type":"","owner_org":[{"reference":"","narrative":[{"narrative":"","language":""}]}]}]	[{"narrative":"Pastoralist Gender Equality Programmebal","language":""}]	[{"type":1,"narrative":[{"narrative":"This project aims to tackle the issue of gender inequality through empowerment, education and the promotion of women's rights. The project will work in 25 villages in nothern Tanzania to establish community \\"\\"Women's Rights Committees' desgned to promote women's equal involvement in community activities. These committees will train women on their-rights to education, property, basic services, health care, representation and seculity against domestic violence and will advocate for their needs at the household, village, district and national level. These committees aim to preempt future abuses and mainstream gender equality at varying scales. This project will also undertake a study to investigate livelhood options for people living in Ngorongoro Conservation Area {NCA} an area where women have recently fallen victim to mistreatment. Subsequently, the findings will be used to plan a conference joining all key stakeholders togethey to strategize ways forward. The ultimate goal of the conference will be to determine a fair and just approach where the goverment and the people of NAC can make agreements that allow them both to live and work in cooperation. The outcome of this study and conference will serve as a model for conflict resolution and gender equality at varying scales.This project will also undertake a study to investigate livelihood options for people living in Ngorongoro Conservation Area(NCA), an area where women have recently fallen victim to misttreatment.Subsequently, the findings will be used to plan a conference joining all key stkeholders together to strategize ways forward.The ultimate goal of the conference will be to determine a fair and just approach where the government and the people of NCA can make agreements that allo them both to live and work in cooperation.The outcome of this study and conference will serve as a model for conflict resolution and gender equality.","language":""}]},{"type":2,"narrative":[{"narrative":"This project aims to address the fundamental challenges that perpetuate gender equality in pastoralist communities through educating women on their rights and creating a system that seeks to initiate dialogue between women and governing bodies at various scales.","language":""}]}]	3	[{"date":"2011-06-10","type":2,"narrative":[[{"narrative":"","language":""}]]},{"date":"2013-10-14","type":4,"narrative":[[{"narrative":"","language":""}]]}]	\N	\N	[{"organization_role":1,"identifier":"","organization_type":"","narrative":[{"narrative":"","language":""}]},{"organization_role":4,"identifier":"","organization_type":"22","narrative":[{"narrative":"Pastoralist Women's Council","language":""}]}]	[{"country_code":"TZ","percentage":"","narrative":[{"narrative":"","language":""}]}]	[{"region_code":"","region_vocabulary":"","percentage":"","narrative":[{"narrative":"","language":""}]}]	[{"reference":"","location_reach":[{"code":""}],"location_id":[{"vocabulary":"","code":""}],"name":[{"narrative":[]}],"location_description":[{"narrative":[]}],"activity_description":[{"narrative":[]}],"administrative":[{"code":"Arusha","vocabulary":"G1","level":"1"},{"code":"Ngorongoro","vocabulary":"G1","level":"2"}],"point":[{"srs_name":"","position":[{"latitude":"","longitude":""}]}],"exactness":[{"code":""}],"location_class":[{"code":""}],"feature_designation":[{"code":""}]}]	[{"sector_vocabulary":2,"sector_code":"","sector_category_code":"151","sector_text":"","percentage":"","narrative":[{"narrative":"","language":""}],"vocabulary_uri":""}]	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	3	2	[{"default_currency":"","default_language":"","default_hierarchy":1}]	0	2016-06-04 11:16:50	2016-06-04 11:18:20
3	{"activity_identifier":"FSC\\/MG\\/1\\/11\\/235","iati_identifier_text":"tz-brla-fcs123-FSC\\/MG\\/1\\/11\\/235"}	[{"reference":"","type":"","owner_org":[{"reference":"","narrative":[{"narrative":"","language":""}]}]}]	[{"narrative":"Capacity building for the Organization","language":""}]	[{"type":1,"narrative":[{"narrative":"The should be separate books of accounts for the project funded by the foundation. All receipts for the purchases of goods and services should bear the name FCS\\/Name of the organization to distinguish with payments made from funds from other donors funds.","language":""}]},{"type":2,"narrative":[{"narrative":"The grantee and the foundation shall quote the contract reference number from the cover page of this contract on all correspondence","language":""}]}]	3	[{"date":"2012-08-04","type":2,"narrative":[[{"narrative":"","language":""}]]},{"date":"2014-12-03","type":4,"narrative":[[{"narrative":"","language":""}]]}]	\N	\N	[{"organization_role":1,"identifier":"","organization_type":"","narrative":[{"narrative":"","language":""}]},{"organization_role":4,"identifier":"","organization_type":"22","narrative":[{"narrative":"The Foundation for Civil Society","language":""}]}]	[{"country_code":"TZ","percentage":"","narrative":[{"narrative":"","language":""}]}]	[{"region_code":"","region_vocabulary":"","percentage":"","narrative":[{"narrative":"","language":""}]}]	[{"reference":"","location_reach":[{"code":""}],"location_id":[{"vocabulary":"","code":""}],"name":[{"narrative":[]}],"location_description":[{"narrative":[]}],"activity_description":[{"narrative":[]}],"administrative":[{"code":"Dar es Salaam","vocabulary":"G1","level":"1"},{"code":"Kinondoni Municipal","vocabulary":"G1","level":"2"}],"point":[{"srs_name":"","position":[{"latitude":"","longitude":""}]}],"exactness":[{"code":""}],"location_class":[{"code":""}],"feature_designation":[{"code":""}]}]	[{"sector_vocabulary":2,"sector_code":"","sector_category_code":"151","sector_text":"","percentage":"","narrative":[{"narrative":"","language":""}],"vocabulary_uri":""}]	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	3	2	[{"default_currency":"USD","default_language":"en","default_hierarchy":1}]	0	2016-06-04 11:23:02	2016-06-04 11:52:53
8	{"activity_identifier":"FCS\\/SG\\/2\\/12\\/ 044","iati_identifier_text":"tz-brla-fcs123-FCS\\/SG\\/2\\/12\\/ 044"}	[{"reference":"","type":"","owner_org":[{"reference":"","narrative":[{"narrative":"","language":""}]}]}]	[{"narrative":"Disability Inclusion in the New Constitution of Tanzania (DINCT)","language":""}]	[{"type":1,"narrative":[{"narrative":"SHIVYAWATA envisions a society which advocates for and empowers persons with disabilities through the recognition of human rights creation of barrier free environment and inclusive society for all people of Tanzania.Launching a prolonged campaign geared toward repudiating social injustices facing disabled people through social, economic, and political systems.Core valuesSHIVYAWATA is committed to issues ofAchievement Cooperation Inclusion Equality of participation Intergrity Solidarity Hope Empowerment","language":""}]},{"type":2,"narrative":[{"narrative":"The overall objective of the project is to ensure the rights of persons with disabilities are guaranteed by the new constitution of the united republic of Tanzania.The consortium will make sure that the opinion and views of people with disabilities for the new constitution are collected, analyzed, complied and submitted to relevant constitution organ by 2012 and that the constitution review commission and parliament of the united republic of Tanzania and house of representatives are sensitive on disability related issues by 2014. The project will cover rural and areas of Tanzania.","language":""}]}]	3	[{"date":"2012-11-22","type":2,"narrative":[[{"narrative":"","language":""}]]},{"date":"2014-11-02","type":4,"narrative":[[{"narrative":"","language":""}]]}]	\N	\N	[{"organization_role":1,"identifier":"","organization_type":"","narrative":[{"narrative":"","language":""}]},{"organization_role":4,"identifier":"","organization_type":"22","narrative":[{"narrative":"TANZANIA DISABILITY CONSORTIUM ON NEW CONSTITUTION","language":""}]}]	[{"country_code":"TZ","percentage":"","narrative":[{"narrative":"","language":""}]}]	[{"region_code":"","region_vocabulary":"","percentage":"","narrative":[{"narrative":"","language":""}]}]	[{"reference":"","location_reach":[{"code":""}],"location_id":[{"vocabulary":"","code":""}],"name":[{"narrative":[]}],"location_description":[{"narrative":[]}],"activity_description":[{"narrative":[]}],"administrative":[{"code":"Kusini","vocabulary":"G1","level":"1"},{"code":"Kati (Central)","vocabulary":"G1","level":"2"}],"point":[{"srs_name":"","position":[{"latitude":"","longitude":""}]}],"exactness":[{"code":""}],"location_class":[{"code":""}],"feature_designation":[{"code":""}]}]	[{"sector_vocabulary":2,"sector_code":"","sector_category_code":"151","sector_text":"","percentage":"","narrative":[{"narrative":"","language":""}],"vocabulary_uri":""}]	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	1	2	[{"default_currency":"USD","default_language":"en","default_hierarchy":1}]	0	2016-06-05 04:36:24	2016-06-05 13:41:29
16	{"activity_identifier":"yitz_2016_01","iati_identifier_text":"-yitz_2016_01"}	[{"reference":"","type":"","owner_org":[{"reference":"","narrative":[{"narrative":"","language":""}]}]}]	[{"narrative":"CSO Transparency in Tanzania","language":""}]	[{"type":1,"narrative":[{"narrative":"CSO Transparency in Tanzania CSO Transparency in Tanzania CSO Transparency in Tanzania CSO Transparency in Tanzania","language":""}]},{"type":2,"narrative":[{"narrative":"Objective is my name","language":""}]},{"type":3,"narrative":[{"narrative":"Small scale CSOs in Dar","language":""}]}]	2	[{"date":"2016-06-06","type":2,"narrative":[[{"narrative":"","language":""}]]}]	\N	\N	[{"organization_role":1,"identifier":"","organization_type":"","narrative":[{"narrative":"","language":""}]},{"organization_role":4,"identifier":"","organization_type":"","narrative":[{"narrative":"YoungInnovations Tanzania","language":""}]}]	[{"country_code":"TZ","percentage":"","narrative":[{"narrative":"","language":""}]}]	[{"region_code":"","region_vocabulary":"","percentage":"","narrative":[{"narrative":"","language":""}]}]	[{"reference":"","location_reach":[{"code":""}],"location_id":[{"vocabulary":"","code":""}],"name":[{"narrative":[]}],"location_description":[{"narrative":[]}],"activity_description":[{"narrative":[]}],"administrative":[{"code":"Dar es Salaam","vocabulary":"G1","level":"1"},{"code":"","vocabulary":"G1","level":"2"}],"point":[{"srs_name":"","position":[{"latitude":"","longitude":""}]}],"exactness":[{"code":""}],"location_class":[{"code":""}],"feature_designation":[{"code":""}]}]	[{"sector_vocabulary":2,"sector_code":"","sector_category_code":"151","sector_text":"","percentage":"","narrative":[{"narrative":"","language":""}],"vocabulary_uri":""}]	\N	\N	\N	\N	\N	\N	\N	\N	[{"budget_type":"1","period_start":[{"date":"2016-06-06"}],"period_end":[{"date":"2016-06-30"}],"value":[{"amount":"5000","currency":"USD","value_date":"2016-06-06"}]}]	\N	\N	\N	\N	\N	\N	3	8	[{"default_currency":"","default_language":"","default_hierarchy":1}]	0	2016-06-06 04:20:13	2016-06-06 04:22:59
17	{"activity_identifier":"yitz_2016_02","iati_identifier_text":"TZ-BRLA-yitz-003-yitz_2016_02"}	[{"reference":"","type":"","owner_org":[{"reference":"","narrative":[{"narrative":"","language":""}]}]}]	[{"narrative":"CSO Transparency in Zanzibar","language":""}]	[{"type":1,"narrative":[{"narrative":" Zanzibar Zanzibar Zanzibar Zanzibar Zanzibar Zanzibar Zanzibar Zanzibar Zanzibar Zanzibar Zanzibar Zanzibar Zanzibar Zanzibar Zanzibar Zanzibar Zanzibar Zanzibar Zanzibar","language":""}]},{"type":2,"narrative":[{"narrative":"Objective is my name","language":""}]},{"type":3,"narrative":[{"narrative":"Small scale CSOs in ZNZ","language":""}]}]	2	[{"date":"2016-06-06","type":2,"narrative":[[{"narrative":"","language":""}]]},{"date":"2016-06-06","type":4,"narrative":[[{"narrative":"","language":""}]]}]	\N	\N	[{"organization_role":1,"identifier":"","organization_type":"","narrative":[{"narrative":"","language":""}]},{"organization_role":4,"identifier":"","organization_type":"","narrative":[{"narrative":"YoungInnovations Tanzania","language":""}]}]	[{"country_code":"TZ","percentage":"","narrative":[{"narrative":"","language":""}]}]	[{"region_code":"","region_vocabulary":"","percentage":"","narrative":[{"narrative":"","language":""}]}]	[{"reference":"","location_reach":[{"code":""}],"location_id":[{"vocabulary":"","code":""}],"name":[{"narrative":[]}],"location_description":[{"narrative":[]}],"activity_description":[{"narrative":[]}],"administrative":[{"code":"Kusini","vocabulary":"G1","level":"1"},{"code":"Kati (Central)","vocabulary":"G1","level":"2"}],"point":[{"srs_name":"","position":[{"latitude":"","longitude":""}]}],"exactness":[{"code":""}],"location_class":[{"code":""}],"feature_designation":[{"code":""}]}]	[{"sector_vocabulary":2,"sector_code":"","sector_category_code":"151","sector_text":"","percentage":"","narrative":[{"narrative":"","language":""}],"vocabulary_uri":""}]	\N	\N	\N	\N	\N	\N	\N	\N	[{"budget_type":"1","period_start":[{"date":"2016-06-06"}],"period_end":[{"date":"2016-06-30"}],"value":[{"amount":"5000","currency":"USD","value_date":"2016-06-06"}]}]	\N	\N	\N	\N	\N	\N	3	8	[{"default_currency":"USD","default_language":"en","default_hierarchy":1}]	0	2016-06-06 09:02:01	2016-06-06 09:03:19
22	{"activity_identifier":"RD\\/VOICE\\/001","iati_identifier_text":"RDTZ-MoHA-1993-RD\\/VOICE\\/001"}	[{"reference":"","type":"","owner_org":[{"reference":"","narrative":[{"narrative":"","language":""}]}]}]	[{"narrative":"Kijana Wajibika -Big Idea","language":""}]	[{"type":1,"narrative":[{"narrative":"The Kijana Wajibika is an initiative that aims at demonstrating the role of young people as the torch bearers for the implementation and monitoring of the Global Goals. \\r\\n\\r\\nThe hypothesis behind Youth Power is simple: empower young people with data, skills and networks; connect them to meaningful opportunities to participate; and they will take the lead in holding their governments to account. We believe that with this support, young people will become leaders in accountability for national and international development commitments. We are testing this approach across 5 regions of Tanzania on local to global development commitments and frameworks, including the Sustainable Development Goals (SDGs). \\r\\n","language":""}]},{"type":2,"narrative":[{"narrative":"1) Creating and opening spaces for young people, to effectively engage in dynamic community conversations on the quality of service delivery and public resource management\\r\\n2) Working with duty bearers to ensure that they are ready and able to support young people as they speak up for changes in their community","language":""}]},{"type":3,"narrative":[{"narrative":"Young People","language":""}]}]	2	[{"date":"2015-12-01","type":2,"narrative":[[{"narrative":"","language":""}]]},{"date":"2016-12-31","type":4,"narrative":[[{"narrative":"","language":""}]]}]	\N	\N	[{"organization_role":1,"identifier":"","organization_type":"22","narrative":[{"narrative":"DFID","language":""}]},{"organization_role":4,"identifier":"","organization_type":"22","narrative":[{"narrative":"Restless Development","language":""}]}]	[{"country_code":"TZ","percentage":"","narrative":[{"narrative":"","language":""}]}]	[{"region_code":"","region_vocabulary":"","percentage":"","narrative":[{"narrative":"","language":""}]}]	[{"reference":"","location_reach":[{"code":""}],"location_id":[{"vocabulary":"","code":""}],"name":[{"narrative":[]}],"location_description":[{"narrative":[]}],"activity_description":[{"narrative":[]}],"administrative":[{"code":"Dar es Salaam","vocabulary":"G1","level":"1"},{"code":"Temeke Municipal","vocabulary":"G1","level":"2"}],"point":[{"srs_name":"","position":[{"latitude":"","longitude":""}]}],"exactness":[{"code":""}],"location_class":[{"code":""}],"feature_designation":[{"code":""}]}]	[{"sector_vocabulary":2,"sector_code":"","sector_category_code":"151","sector_text":"","percentage":"","narrative":[{"narrative":"","language":""}],"vocabulary_uri":""}]	\N	\N	\N	\N	\N	\N	\N	\N	[{"budget_type":"1","period_start":[{"date":"2015-12-01"}],"period_end":[{"date":"2016-07-31"}],"value":[{"amount":"180000000","currency":"TZS","value_date":"2015-12-01"}]}]	\N	\N	\N	\N	\N	\N	3	11	[{"default_currency":"TZS","default_language":"en","default_hierarchy":1}]	0	2016-06-07 08:53:43	2016-06-07 09:08:36
20	{"activity_identifier":"2016-2017","iati_identifier_text":"YITZ-2016-2017"}	[{"reference":"","type":"","owner_org":[{"reference":"","narrative":[{"narrative":"","language":""}]}]}]	[{"narrative":"Women empowerment","language":""}]	[{"type":1,"narrative":[{"narrative":"To empower women from family level","language":""}]},{"type":2,"narrative":[{"narrative":"All women at family level being empowered","language":""}]},{"type":3,"narrative":[{"narrative":"Women and youth","language":""}]}]	2	[{"date":"2016-06-07","type":2,"narrative":[[{"narrative":"","language":""}]]},{"date":"2021-06-07","type":4,"narrative":[[{"narrative":"","language":""}]]}]	\N	\N	[{"organization_role":1,"identifier":"","organization_type":"21","narrative":[{"narrative":"Cannadian organization","language":""}]},{"organization_role":4,"identifier":"","organization_type":"22","narrative":[{"narrative":"Kido","language":""}]}]	[{"country_code":"TZ","percentage":"","narrative":[{"narrative":"","language":""}]}]	[{"region_code":"","region_vocabulary":"","percentage":"","narrative":[{"narrative":"","language":""}]}]	[{"reference":"","location_reach":[{"code":""}],"location_id":[{"vocabulary":"","code":""}],"name":[{"narrative":[]}],"location_description":[{"narrative":[]}],"activity_description":[{"narrative":[]}],"administrative":[{"code":"Dar es Salaam","vocabulary":"G1","level":"1"},{"code":"Kinondoni Municipal","vocabulary":"G1","level":"2"}],"point":[{"srs_name":"","position":[{"latitude":"","longitude":""}]}],"exactness":[{"code":""}],"location_class":[{"code":""}],"feature_designation":[{"code":""}]},{"reference":"","location_reach":[{"code":""}],"location_id":[{"vocabulary":"","code":""}],"name":[{"narrative":[]}],"location_description":[{"narrative":[]}],"activity_description":[{"narrative":[]}],"administrative":[{"code":"Dodoma","vocabulary":"G1","level":"1"},{"code":"Kongwa","vocabulary":"G1","level":"2"}],"point":[{"srs_name":"","position":[{"latitude":"","longitude":""}]}],"exactness":[{"code":""}],"location_class":[{"code":""}],"feature_designation":[{"code":""}]}]	[{"sector_vocabulary":2,"sector_code":"","sector_category_code":"111","sector_text":"","percentage":"","narrative":[{"narrative":"","language":""}],"vocabulary_uri":""}]	\N	\N	\N	\N	\N	\N	\N	\N	[{"budget_type":"1","period_start":[{"date":"2016-06-07"}],"period_end":[{"date":"2020-06-07"}],"value":[{"amount":"100000000000000000000","currency":"TZS","value_date":"2016-06-07"}]}]	\N	\N	\N	\N	\N	\N	3	14	[{"default_currency":"TZS","default_language":"en","default_hierarchy":1}]	0	2016-06-07 08:53:10	2016-06-07 09:07:35
19	{"activity_identifier":"2016-02-01","iati_identifier_text":"YITZ-2016-02-01"}	[{"reference":"","type":"","owner_org":[{"reference":"","narrative":[{"narrative":"","language":""}]}]}]	[{"narrative":"Promoting CSO transparency in Tanzania","language":""}]	[{"type":1,"narrative":[{"narrative":"This is a project funded by World Bank to promote IATI amongst CSOs in Tanzania. The project is for 2 years starting from June 2016","language":""}]},{"type":2,"narrative":[{"narrative":"Adoption of IATI by CSOs in Tanzania","language":""}]},{"type":3,"narrative":[{"narrative":"CSOs ","language":""}]}]	2	[{"date":"2016-06-07","type":2,"narrative":[[{"narrative":"","language":""}]]},{"date":"2018-06-06","type":4,"narrative":[[{"narrative":"","language":""}]]}]	\N	\N	[{"organization_role":1,"identifier":"","organization_type":"40","narrative":[{"narrative":"World Bank","language":""}]},{"organization_role":4,"identifier":"","organization_type":"22","narrative":[{"narrative":"YoungInnovations Tanzania","language":""}]}]	[{"country_code":"TZ","percentage":"","narrative":[{"narrative":"","language":""}]}]	[{"region_code":"","region_vocabulary":"","percentage":"","narrative":[{"narrative":"","language":""}]}]	[{"reference":"","location_reach":[{"code":""}],"location_id":[{"vocabulary":"","code":""}],"name":[{"narrative":[]}],"location_description":[{"narrative":[]}],"activity_description":[{"narrative":[]}],"administrative":[{"code":"Arusha","vocabulary":"G1","level":"1"},{"code":"Arusha","vocabulary":"G1","level":"2"}],"point":[{"srs_name":"","position":[{"latitude":"","longitude":""}]}],"exactness":[{"code":""}],"location_class":[{"code":""}],"feature_designation":[{"code":""}]},{"reference":"","location_reach":[{"code":""}],"location_id":[{"vocabulary":"","code":""}],"name":[{"narrative":[]}],"location_description":[{"narrative":[]}],"activity_description":[{"narrative":[]}],"administrative":[{"code":"Dar es Salaam","vocabulary":"G1","level":"1"},{"code":"Temeke Municipal","vocabulary":"G1","level":"2"}],"point":[{"srs_name":"","position":[{"latitude":"","longitude":""}]}],"exactness":[{"code":""}],"location_class":[{"code":""}],"feature_designation":[{"code":""}]}]	[{"sector_vocabulary":2,"sector_code":"","sector_category_code":"151","sector_text":"","percentage":"","narrative":[{"narrative":"","language":""}],"vocabulary_uri":""}]	\N	\N	\N	\N	\N	\N	\N	\N	[{"budget_type":"1","period_start":[{"date":"2016-06-07"}],"period_end":[{"date":"2018-06-07"}],"value":[{"amount":"100000000","currency":"TZS","value_date":"2016-06-07"}]}]	\N	\N	\N	\N	\N	\N	3	12	[{"default_currency":"TZS","default_language":"en","default_hierarchy":1}]	0	2016-06-07 08:53:04	2016-06-07 09:06:58
21	{"activity_identifier":"2016-02-01","iati_identifier_text":"TWA-BRELA-2014-2016-02-01"}	[{"reference":"","type":"","owner_org":[{"reference":"","narrative":[{"narrative":"","language":""}]}]}]	[{"narrative":"Support on Open Government Data in Tanzania","language":""}]	[{"type":1,"narrative":[{"narrative":"This is an initiative to support open data by government ministries and institutions in Tanzania and enabling citizens to use data in their daily activities","language":""}]}]	2	[{"date":"2015-01-01","type":2,"narrative":[[{"narrative":"","language":""}]]}]	\N	\N	[{"organization_role":1,"identifier":"","organization_type":"40","narrative":[{"narrative":"World Bank","language":""}]},{"organization_role":4,"identifier":"","organization_type":"","narrative":[{"narrative":"Twaweza East Africa","language":""}]}]	[{"country_code":"TZ","percentage":"","narrative":[{"narrative":"","language":""}]}]	[{"region_code":"","region_vocabulary":"","percentage":"","narrative":[{"narrative":"","language":""}]}]	[{"reference":"","location_reach":[{"code":""}],"location_id":[{"vocabulary":"","code":""}],"name":[{"narrative":[]}],"location_description":[{"narrative":[]}],"activity_description":[{"narrative":[]}],"administrative":[{"code":"","vocabulary":"G1","level":"1"},{"code":"","vocabulary":"G1","level":"2"}],"point":[{"srs_name":"","position":[{"latitude":"","longitude":""}]}],"exactness":[{"code":""}],"location_class":[{"code":""}],"feature_designation":[{"code":""}]}]	[{"sector_vocabulary":2,"sector_code":"","sector_category_code":"112","sector_text":"","percentage":"","narrative":[{"narrative":"","language":""}],"vocabulary_uri":""}]	\N	\N	\N	\N	\N	\N	\N	\N	[{"budget_type":"1","period_start":[{"date":"2015-01-01"}],"period_end":[{"date":"2015-12-31"}],"value":[{"amount":"100000","currency":"USD","value_date":"2015-01-01"}]}]	\N	\N	\N	\N	\N	\N	3	10	[{"default_currency":"TZS","default_language":"en","default_hierarchy":1}]	0	2016-06-07 08:53:25	2016-06-07 09:10:08
18	{"activity_identifier":"FCS\\/TST\\/001","iati_identifier_text":"FCSTZ-BRLA-2002-FCS\\/TST\\/001"}	[{"reference":"","type":"","owner_org":[{"reference":"","narrative":[{"narrative":"","language":""}]}]}]	[{"narrative":"LAND RIGHTS PROGRAMME IN MAKULAT DIVISION ARUMERU DISTRICT","language":""}]	[{"type":1,"narrative":[{"narrative":"THERE IS A NEED TO IMPLEMENT THE LAND RIGHT PROGAMME SO AS TO FACILITATE KNOWLEDGE ABOUT LAND,POLICY AND LAWS","language":""}]},{"type":2,"narrative":[{"narrative":"\\tTO EMPOWER COMMUNITIES IN MUKULAT DIVISION,ARUMERU DISTRICT ON LAND RIGHTS","language":""}]},{"type":3,"narrative":[{"narrative":"Communities","language":""}]}]	2	[{"date":"2015-09-01","type":2,"narrative":[[{"narrative":"","language":""}]]},{"date":"2016-09-08","type":4,"narrative":[[{"narrative":"","language":""}]]}]	\N	\N	[{"organization_role":1,"identifier":"","organization_type":"22","narrative":[{"narrative":"FCS","language":""}]},{"organization_role":4,"identifier":"","organization_type":"22","narrative":[{"narrative":"COMMUNITY INITIATIVES TO DEVELOPMENT CHALLENGES","language":""}]}]	[{"country_code":"TZ","percentage":"","narrative":[{"narrative":"","language":""}]}]	[{"region_code":"","region_vocabulary":"","percentage":"","narrative":[{"narrative":"","language":""}]}]	[{"reference":"","location_reach":[{"code":""}],"location_id":[{"vocabulary":"","code":""}],"name":[{"narrative":[]}],"location_description":[{"narrative":[]}],"activity_description":[{"narrative":[]}],"administrative":[{"code":"Arusha","vocabulary":"G1","level":"1"},{"code":"Arusha","vocabulary":"G1","level":"2"}],"point":[{"srs_name":"","position":[{"latitude":"","longitude":""}]}],"exactness":[{"code":""}],"location_class":[{"code":""}],"feature_designation":[{"code":""}]}]	[{"sector_vocabulary":2,"sector_code":"","sector_category_code":"152","sector_text":"","percentage":"","narrative":[{"narrative":"","language":""}],"vocabulary_uri":""}]	\N	\N	\N	\N	\N	\N	\N	\N	[{"budget_type":"1","period_start":[{"date":"2015-09-01"}],"period_end":[{"date":"2016-08-11"}],"value":[{"amount":"44890000","currency":"TZS","value_date":"2015-09-01"}]}]	\N	\N	\N	\N	\N	\N	0	9	[{"default_currency":"TZS","default_language":"en","default_hierarchy":1}]	0	2016-06-07 08:41:31	2016-06-07 09:59:05
9	{"activity_identifier":"FCS\\/MG\\/SCGE\\/15\\/79","iati_identifier_text":"tz-brla-fcs123-FCS\\/MG\\/SCGE\\/15\\/79"}	[{"reference":"","type":"","owner_org":[{"reference":"","narrative":[{"narrative":"","language":""}]}]}]	[{"narrative":"UTOAJI WA ELIMU YA UCHAGUZI","language":""}]	[{"type":1,"narrative":[{"narrative":"JUMLA YA VIJANA, WANAWAKE NA MAKUNDI NA WATU WA MAKUNDI MAALUM 110 NDANI YA SHERIA ZA KIBWENI, BUBUBU NA SHARIFU MSA WAMEPATA UFAHAMU JUU YA ELIMU YA UCHAGUZI NA MAENDELEO IFIKAPO NOVEMBER 2015","language":""}]},{"type":2,"narrative":[{"narrative":"MRADI HUU NI KUWAPATA NA KUKUZA UELEWA KWA VIJANA, WANAWAKE NA MAUNDI MAALUMU KATIKA SHERIA ZA KIBWENI, KIJICHI NA MWANYANAYA, BUBUBU ,KWAGOA NA SHARIFUMJSA WILAYA YA MAGHARIBU 'A'","language":""}]}]	3	[{"date":"2015-09-29","type":2,"narrative":[[{"narrative":"","language":""}]]}]	\N	\N	[{"organization_role":1,"identifier":"","organization_type":"","narrative":[{"narrative":"","language":""}]},{"organization_role":4,"identifier":"","organization_type":"22","narrative":[{"narrative":"MWANYANYA GREEN SOCIETY (MGS)","language":""}]}]	[{"country_code":"TZ","percentage":"","narrative":[{"narrative":"","language":""}]}]	[{"region_code":"","region_vocabulary":"","percentage":"","narrative":[{"narrative":"","language":""}]}]	[{"reference":"","location_reach":[{"code":""}],"location_id":[{"vocabulary":"","code":""}],"name":[{"narrative":[]}],"location_description":[{"narrative":[]}],"activity_description":[{"narrative":[]}],"administrative":[{"code":"Kusini","vocabulary":"G1","level":"1"},{"code":"Kati (South)","vocabulary":"G1","level":"2"}],"point":[{"srs_name":"","position":[{"latitude":"","longitude":""}]}],"exactness":[{"code":""}],"location_class":[{"code":""}],"feature_designation":[{"code":""}]}]	[{"sector_vocabulary":2,"sector_code":"","sector_category_code":"430","sector_text":"","percentage":"","narrative":[{"narrative":"","language":""}],"vocabulary_uri":""}]	\N	\N	\N	\N	\N	\N	\N	\N	[{"budget_type":"1","period_start":[{"date":"2016-06-13"}],"period_end":[{"date":"2016-06-16"}],"value":[{"amount":"200","currency":"USD","value_date":"2016-06-13"}]}]	\N	\N	\N	\N	\N	\N	0	2	[{"default_currency":"USD","default_language":"en","default_hierarchy":1}]	0	2016-06-05 05:35:34	2016-06-08 04:48:46
27	{"activity_identifier":"test","iati_identifier_text":"tz-brla-fcs123-test"}	\N	[{"narrative":"test title"}]	[{"type":1,"narrative":[{"narrative":"test desc"}]}]	2	[{"date":"2016-06-09","type":2},{"date":"2016-06-28","type":4}]	\N	\N	[{"organization_role":1},{"organization_role":4,"organization_type":"15","narrative":[{"narrative":"test org"}]}]	[{"country_code":"TZ"}]	\N	[{"administrative":[{"code":"Geita","vocabulary":"G1","level":"1"},{"code":"Nyang'hwale","vocabulary":"G1","level":"2"}]}]	[{"sector_vocabulary":2,"sector_category_code":"113"}]	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	2	2	[{"default_currency":"USD","default_language":"en","default_hierarchy":1}]	0	2016-06-08 12:27:59	2016-06-08 12:40:37
35	{"activity_identifier":"123","iati_identifier_text":"test111-123"}	[{"reference":"","type":"","owner_org":[{"reference":"","narrative":[{"narrative":"","language":""}]}]}]	[{"narrative":"3","language":""}]	[{"type":1,"narrative":[{"narrative":"asdf","language":""}]},{"type":2,"narrative":[{"narrative":"asdf","language":""}]},{"type":3,"narrative":[{"narrative":"asdf","language":""}]}]	4	[{"date":"2016-06-05","type":2,"narrative":[[{"narrative":"","language":""}]]},{"date":"2016-06-09","type":4,"narrative":[[{"narrative":"","language":""}]]}]	\N	\N	[{"organization_role":1,"identifier":"","organization_type":"","narrative":[{"narrative":"","language":""}]},{"organization_role":4,"identifier":"","organization_type":"21","narrative":[{"narrative":"asdf","language":""}]}]	[{"country_code":"TZ","percentage":"","narrative":[{"narrative":"","language":""}]}]	[{"region_code":"","region_vocabulary":"","percentage":"","narrative":[{"narrative":"","language":""}]}]	[{"reference":"","location_reach":[{"code":""}],"location_id":[{"vocabulary":"","code":""}],"name":[{"narrative":[]}],"location_description":[{"narrative":[]}],"activity_description":[{"narrative":[]}],"administrative":[{"code":"Iringa","vocabulary":"G1","level":"1"},{"code":"Kilolo","vocabulary":"G1","level":"2"}],"point":[{"srs_name":"","position":[{"latitude":"","longitude":""}]}],"exactness":[{"code":""}],"location_class":[{"code":""}],"feature_designation":[{"code":""}]}]	[{"sector_vocabulary":2,"sector_code":"","sector_category_code":"114","sector_text":"","percentage":"","narrative":[{"narrative":"","language":""}],"vocabulary_uri":""}]	\N	\N	\N	\N	\N	\N	\N	\N	[{"budget_type":"1","period_start":[{"date":"2016-06-10"}],"period_end":[{"date":"2016-06-23"}],"value":[{"amount":"5000","currency":"NPR","value_date":"2016-06-10"}]}]	\N	\N	\N	\N	\N	\N	1	16	[{"default_currency":"NPR","default_language":"ne","default_hierarchy":1}]	0	2016-06-09 05:00:14	2016-06-09 05:00:58
37	{"activity_identifier":"2","iati_identifier_text":"test111-2"}	\N	[{"narrative":"2"}]	[{"type":1,"narrative":[{"narrative":"asdf"}]},{"type":2,"narrative":[{"narrative":"sdfasdf"}]},{"type":3,"narrative":[{"narrative":"asdfasdf"}]}]	4	[{"date":"2016-06-15","type":2},{"date":"2016-06-24","type":4}]	\N	\N	[{"organization_role":1,"organization_type":"23","narrative":[{"narrative":"asdfasdf"}]},{"organization_role":1,"organization_type":"80","narrative":[{"narrative":"asdfasdf"}]},{"organization_role":4,"organization_type":"30","narrative":[{"narrative":"asdfadsf"}]},{"organization_role":4,"organization_type":"23","narrative":[{"narrative":"asdfasdf"}]}]	[{"country_code":"TZ"}]	\N	[{"administrative":[{"code":"Iringa","vocabulary":"G1","level":"1"},{"code":"Iringa (Rural)","vocabulary":"G1","level":"2"}]}]	[{"sector_vocabulary":2,"sector_category_code":"121"}]	\N	\N	\N	\N	\N	\N	\N	\N	{"0":{"budget_type":"1","period_start":[{"date":"2016-06-07"}],"period_end":[{"date":"2016-06-26"}],"value":[{"amount":"1200","currency":"NPR","value_date":""}]},"2":{"value":[{"value_date":"2016-06-07"}]}}	\N	\N	\N	\N	\N	\N	0	16	[{"default_currency":"NPR","default_language":"ne","default_hierarchy":1}]	0	2016-06-10 11:11:00	2016-06-13 08:25:54
\.


--
-- Name: activity_data_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('activity_data_id_seq', 38, true);


--
-- Data for Name: activity_document_links; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY activity_document_links (id, activity_id, document_link, created_at, updated_at) FROM stdin;
5	22	{"category":[{"code":"A08"}],"format":"text\\/html","title":[{"narrative":[{"language":"","narrative":""}]}],"language":"[]","url":""}	2016-06-07 08:53:43	2016-06-07 08:53:43
10	35	{"category":[{"code":"A08"}],"format":"text\\/html","title":[{"narrative":[{"language":"","narrative":"asdfasdfasdf"}]}],"language":"[]","url":"http:\\/\\/example.com"}	2016-06-09 05:00:14	2016-06-09 05:00:14
\.


--
-- Name: activity_document_links_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('activity_document_links_id_seq', 10, true);


--
-- Data for Name: activity_published; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY activity_published (id, published_activities, filename, published_to_register, organization_id, created_at, updated_at) FROM stdin;
6	["yipltz-16.xml","yipltz-17.xml"]	yipltz-activities.xml	0	8	2016-06-06 04:22:59	2016-06-06 09:03:19
7	["YITZ-BRLA-2016-19.xml"]	YITZ-BRLA-2016-activities.xml	0	12	2016-06-07 09:06:58	2016-06-07 09:06:58
9	["YITZ-BRLA -2016-20.xml"]	YITZ-BRLA -2016-activities.xml	0	14	2016-06-07 09:07:35	2016-06-07 09:07:35
11	["FCSTZ-BRLA-2002-18.xml"]	FCSTZ-BRLA-2002-activities.xml	0	9	2016-06-07 09:09:02	2016-06-07 09:09:02
8	["TWA-BRELA-2014-21.xml"]	TWA-BRELA-2014-activities.xml	0	10	2016-06-07 09:07:07	2016-06-07 09:10:08
10	{"22":"RDTZ-MoHA-1993-22.xml"}	RDTZ-MoHA-1993-activities.xml	0	11	2016-06-07 09:08:36	2016-06-07 09:16:46
\.


--
-- Name: activity_published_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('activity_published_id_seq', 12, true);


--
-- Data for Name: activity_results; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY activity_results (id, activity_id, result, created_at, updated_at) FROM stdin;
\.


--
-- Name: activity_results_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('activity_results_id_seq', 1, false);


--
-- Data for Name: activity_transactions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY activity_transactions (id, activity_id, transaction, created_at, updated_at) FROM stdin;
24	21	{"transaction_type":[{"transaction_type_code":"4"}],"reference":"Twa-2015","transaction_date":[{"date":"2015-12-31"}],"value":[{"amount":"100000","date":"2015-12-31","currency":"TZS"}],"description":[{"narrative":[{"narrative":"Consultancy costs","language":""}]}],"provider_organization":[{"narrative":[{"narrative":"Uwezo","language":""}]}]}	2016-06-07 09:09:50	2016-06-07 09:09:50
25	18	{"transaction_type":[{"transaction_type_code":"3"}],"reference":"asdf","transaction_date":[{"date":"2016-06-07"}],"value":[{"amount":"12345","date":"2016-06-07","currency":"TZS"}],"description":[{"narrative":[{"narrative":"asdf","language":""}]}],"provider_organization":[{"narrative":[{"narrative":"asdf","language":""}]}]}	2016-06-07 09:59:05	2016-06-07 09:59:05
83	37	{"transaction_type":[{"transaction_type_code":"4"}],"reference":"32awer","transaction_date":[{"date":"2016-05-31"}],"value":[{"amount":"1000","date":"2016-05-31","currency":"NPR"}],"description":[{"narrative":[{"narrative":""}]}],"provider_organization":[{"narrative":[{"narrative":""}]}]}	2016-06-13 08:17:48	2016-06-13 08:20:16
75	37	{"transaction_type":[{"transaction_type_code":"4"}],"reference":"asdf","transaction_date":[{"date":"2016-06-09"}],"value":[{"amount":"6000","date":"2016-06-09","currency":"NPR"}],"description":[{"narrative":[{"narrative":""}]}],"provider_organization":[{"narrative":[{"narrative":""}]}]}	2016-06-10 11:59:01	2016-06-10 11:59:49
64	35	{"transaction_type":[{"transaction_type_code":"4"}],"reference":"r1","transaction_date":[{"date":"2016-06-20"}],"value":[{"amount":"5000","date":"2016-06-20","currency":"NPR"}],"description":[{"narrative":[{"narrative":"asdfadsf"}]}],"provider_organization":[{"narrative":[{"narrative":""}]}]}	2016-06-09 05:00:57	2016-06-09 10:26:54
65	35	{"transaction_type":[{"transaction_type_code":"4"}],"reference":"re","transaction_date":[{"date":"2016-06-02"}],"value":[{"amount":"12222","date":"2016-06-02","currency":"NPR"}],"description":[{"narrative":[{"narrative":"","language":""}]}],"provider_organization":[{"narrative":[{"narrative":"","language":""}]}]}	2016-06-09 10:26:54	2016-06-09 10:26:54
79	37	{"transaction_type":[{"transaction_type_code":"3"}],"reference":"asdf","transaction_date":[{"date":"2016-06-21"}],"value":[{"amount":"123","date":"2016-06-21","currency":"NPR"}],"description":[{"narrative":[{"narrative":"","language":""}]}],"provider_organization":[{"narrative":[{"narrative":"","language":""}]}]}	2016-06-10 12:31:04	2016-06-10 12:31:04
80	37	{"transaction_type":[{"transaction_type_code":"1"}],"reference":"rere","transaction_date":[{"date":"2016-06-14"}],"value":[{"amount":"1000","date":"2016-06-14","currency":"NPR"}],"description":[{"narrative":[{"narrative":"","language":""}]}],"provider_organization":[{"narrative":[{"narrative":"","language":""}]}]}	2016-06-13 06:31:26	2016-06-13 06:31:26
\.


--
-- Name: activity_transactions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('activity_transactions_id_seq', 84, true);


--
-- Data for Name: documents; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY documents (id, filename, url, activities, org_id, created_at, updated_at) FROM stdin;
\.


--
-- Name: documents_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('documents_id_seq', 1, false);


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY failed_jobs (id, connection, queue, payload, failed_at) FROM stdin;
\.


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('failed_jobs_id_seq', 1, false);


--
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY jobs (id, queue, payload, attempts, reserved, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('jobs_id_seq', 1, false);


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY migrations (migration, batch) FROM stdin;
2015_08_26_110624_create_organizations_table	1
2015_08_31_101311_create_versions_table	1
2015_09_01_111150_create_settings_table	1
2015_09_02_034744_create_role_table	1
2015_09_04_102543_create_organization_data_table	1
2015_09_14_073120_create_user_activities_table	1
2015_09_18_035235_create_organization_published_table	1
2015_09_23_082439_create_activity_data_table	1
2015_10_12_000000_create_users_table	1
2015_10_12_100000_create_password_resets_table	1
2015_11_03_073525_create_user_group_table	1
2015_11_06_073242_create_activity_transactions_table	1
2015_11_16_090921_create_results_table	1
2015_11_26_083023_create_activity_published_table	1
2016_01_11_082505_create_documents_table	1
2016_01_25_072320_create_jobs_table	1
2016_01_25_091139_create_failed_jobs_table	1
2016_03_17_092448_create_activities_in_registry_table	1
2016_04_05_042408_add_display_organizations_table	1
2016_05_02_083426_add_data_to_user_activities	1
2016_05_05_051012_add_organization_id_column_to_user_activities_table	1
2016_05_05_104114_create_activity_document_links_table	1
\.


--
-- Data for Name: organization_data; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY organization_data (id, name, total_budget, recipient_organization_budget, recipient_region_budget, recipient_country_budget, total_expenditure, document_link, organization_id, status, created_at, updated_at) FROM stdin;
\.


--
-- Name: organization_data_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('organization_data_id_seq', 1, false);


--
-- Data for Name: organization_published; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY organization_published (id, filename, published_to_register, organization_id, created_at, updated_at) FROM stdin;
\.


--
-- Name: organization_published_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('organization_published_id_seq', 1, false);


--
-- Data for Name: organizations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY organizations (id, user_identifier, name, address, telephone, reporting_org, country, twitter, disqus_comments, logo, logo_url, organization_url, status, published_to_registry, created_at, updated_at, display) FROM stdin;
2	fcs	The Foundation for Civil Society	Madai Crescent 7 Ada Estate, Dar es Salarm	+255 22 - 2664890/2664891/2664892	[{"reporting_organization_identifier":"tz-brla-fcs123","reporting_organization_type":"22","narrative":[{"narrative":"The Foundation for Civil Society","language":"en"}]}]	TZ		\N	2.png	http://tz.aidstream.org/files/logos/2.png	http://www.thefoundation.or.tz/	1	0	2016-06-04 11:15:24	2016-06-05 05:11:20	1
4	repoa	Policy Research for Development	157 Mgombani Street, Regent Estate, Dar es Salaam	+255 (22) 2700083 / 2772556	\N	TZ		\N	4.gif	http://tz.aidstream.org/files/logos/4.gif	http://www.repoa.or.tz/	1	0	2016-06-05 05:21:56	2016-06-06 02:42:06	1
7	YITZ	YoungInnovations Tanzania	Tanzania	\N	\N	TZ	\N	\N	\N	\N	\N	1	0	2016-06-06 04:01:31	2016-06-06 04:01:31	1
8	yitz	YoungInnovations Tanzania	Dar		[{"reporting_organization_identifier":"TZ-BRLA-yitz-003","reporting_organization_type":"70","narrative":[{"narrative":"YoungInnovations Tanzania","language":"en"}]}]	TZ		\N	8.png	http://tz.aidstream.org/files/logos/8.png		1	0	2016-06-06 04:05:57	2016-06-06 05:42:58	1
13	TCDD	Tanzania Coalition on Debt and Development	P.O. Box 80147	\N	\N	TZ	\N	\N	\N	\N	\N	1	0	2016-06-07 08:37:04	2016-06-07 08:37:04	1
12	YI	YoungInnovations Daressalaam	Daressalam, Tanzania	\N	[{"reporting_organization_identifier":"YITZ","reporting_organization_type":"22","narrative":[{"narrative":"Young Innovations in Tanzania","language":"en"}]}]	TZ	\N	\N	\N	\N	\N	1	0	2016-06-07 08:35:15	2016-06-07 08:44:22	1
10	TWA	Twaweza East Africa	P. O. BOX 28242 TANZANIA	\N	[{"reporting_organization_identifier":"TWA-BRELA-2014","reporting_organization_type":"21","narrative":[{"narrative":"Twaweza East Africa","language":"en"}]}]	TZ	\N	\N	\N	\N	\N	1	0	2016-06-07 08:28:41	2016-06-07 08:44:52	1
14	Kido	Kido	Box 20	\N	[{"reporting_organization_identifier":"YITZ","reporting_organization_type":"22","narrative":[{"narrative":"Kido","language":"en"}]}]	TZ	\N	\N	\N	\N	\N	1	0	2016-06-07 08:37:08	2016-06-07 08:45:21	1
11	RD	Restless Development Tanzania	Regent Business Park Bulding, Chwaku Street, Mikocheni A, Dar es Salaam		[{"reporting_organization_identifier":"RDTZ-MoHA-1993","reporting_organization_type":"22","narrative":[{"narrative":"Restless Development","language":"en"}]}]	TZ	@sautiyavijana	\N	11.jpg	http://tz.aidstream.org/files/logos/11.jpg	http://restlessdevelopment.org/tanzania	1	0	2016-06-07 08:28:55	2016-06-07 09:12:03	1
9	FCS	The Foundation For Civil Society	7 Madai Crescent, Ada Estate Plot No. 154		[{"reporting_organization_identifier":"FCSTZ-BRLA-2002","reporting_organization_type":"22","narrative":[{"narrative":"The Foundation For Civil Society","language":"en"}]}]	TZ		\N	9.jpg	http://tz.aidstream.org/files/logos/9.jpg		1	0	2016-06-07 08:26:10	2016-06-07 09:12:14	1
15	deco	Development Corncern	Tanzania	\N	\N	TZ	\N	\N	\N	\N	\N	1	0	2016-06-07 09:16:08	2016-06-07 09:16:08	1
16	ss	test_tz	test	\N	[{"reporting_organization_identifier":"test111","reporting_organization_type":"15","narrative":[{"narrative":"tt","language":"ne"}]}]	TZ	\N	\N	\N	\N	\N	1	0	2016-06-08 12:44:09	2016-06-08 12:46:27	1
\.


--
-- Name: organizations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('organizations_id_seq', 16, true);


--
-- Data for Name: password_resets; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY password_resets (email, token, created_at) FROM stdin;
anjesh+fcs@yipl.com.np	f0193d4c5f069ed429647407e8ad61b0b0ae96efc0c3d9ab80551fddba3ce3dc	2016-06-05 05:51:35
\.


--
-- Data for Name: role; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY role (id, role, created_at, updated_at) FROM stdin;
1	admin	2015-09-01 11:42:29	2015-09-01 11:43:29
2	user	2015-09-01 11:42:29	2015-09-01 11:43:29
3	superadmin	2015-09-01 11:42:29	2015-09-01 11:43:29
4	groupadmin	2015-09-01 11:42:29	2015-09-01 11:43:29
\.


--
-- Name: role_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('role_id_seq', 1, false);


--
-- Data for Name: settings; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY settings (id, publishing_type, registry_info, default_field_values, default_field_groups, version, organization_id, created_at, updated_at) FROM stdin;
4	unsegmented	\N	\N	\N	2.02	4	2016-06-05 05:21:56	2016-06-05 05:21:56
2	unsegmented	[{"publisher_id":"fcs","api_id":"","publish_files":"no"}]	[{"default_language":"en","default_currency":"USD"}]	\N	2.02	2	2016-06-04 11:15:24	2016-06-05 13:40:27
7	unsegmented	\N	\N	\N	2.02	7	2016-06-06 04:01:31	2016-06-06 04:01:31
8	unsegmented	[{"publisher_id":"yipltz","api_id":"","publish_files":"no"}]	[{"default_language":"en","default_currency":"USD"}]	\N	2.02	8	2016-06-06 04:05:57	2016-06-06 04:22:56
13	unsegmented	\N	\N	\N	2.02	13	2016-06-07 08:37:04	2016-06-07 08:37:04
12	unsegmented	[{"publisher_id":"YITZ-BRLA-2016","api_id":"","publish_files":"no"}]	[{"default_language":"en","default_currency":"TZS"}]	\N	2.02	12	2016-06-07 08:35:15	2016-06-07 08:44:22
9	unsegmented	[{"publisher_id":"FCSTZ-BRLA-2002","api_id":"","publish_files":"no"}]	[{"default_language":"en","default_currency":"TZS"}]	\N	2.02	9	2016-06-07 08:26:10	2016-06-07 08:44:24
10	unsegmented	[{"publisher_id":"TWA-BRELA-2014","api_id":"","publish_files":"no"}]	[{"default_language":"en","default_currency":"TZS"}]	\N	2.02	10	2016-06-07 08:28:41	2016-06-07 08:44:52
11	unsegmented	[{"publisher_id":"RDTZ-MoHA-1993","api_id":"","publish_files":"no"}]	[{"default_language":"en","default_currency":"TZS"}]	\N	2.02	11	2016-06-07 08:28:55	2016-06-07 08:45:01
14	unsegmented	[{"publisher_id":"YITZ-BRLA -2016","api_id":"","publish_files":"no"}]	[{"default_language":"en","default_currency":"TZS"}]	\N	2.02	14	2016-06-07 08:37:08	2016-06-07 08:45:21
15	unsegmented	\N	\N	\N	2.02	15	2016-06-07 09:16:08	2016-06-07 09:16:08
16	unsegmented	[{"publisher_id":"sbs","api_id":"","publish_files":"no"}]	[{"default_language":"ne","default_currency":"NPR"}]	\N	2.02	16	2016-06-08 12:44:10	2016-06-08 12:46:27
\.


--
-- Name: settings_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('settings_id_seq', 16, true);


--
-- Data for Name: user_activities; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY user_activities (id, user_id, action, param, created_at, updated_at, data, organization_id) FROM stdin;
1	2	activity.activity_status_changed	{"activity_id":1,"status":"Completed"}	2016-06-04 11:07:30	2016-06-04 11:07:30	\N	1
2	2	activity.activity_status_changed	{"activity_id":1,"status":"Completed"}	2016-06-04 11:10:03	2016-06-04 11:10:03	\N	1
3	2	activity.activity_status_changed	{"activity_id":1,"status":"Verified"}	2016-06-04 11:10:06	2016-06-04 11:10:06	\N	1
4	2	activity.activity_status_changed	{"activity_id":1,"status":"Published"}	2016-06-04 11:10:10	2016-06-04 11:10:10	\N	1
5	3	activity.activity_status_changed	{"activity_id":2,"status":"Completed"}	2016-06-04 11:17:28	2016-06-04 11:17:28	\N	2
6	3	activity.activity_status_changed	{"activity_id":2,"status":"Verified"}	2016-06-04 11:17:31	2016-06-04 11:17:31	\N	2
7	3	activity.activity_status_changed	{"activity_id":2,"status":"Published"}	2016-06-04 11:18:21	2016-06-04 11:18:21	\N	2
8	3	activity.activity_status_changed	{"activity_id":3,"status":"Completed"}	2016-06-04 11:23:09	2016-06-04 11:23:09	\N	2
9	3	activity.activity_status_changed	{"activity_id":3,"status":"Verified"}	2016-06-04 11:23:11	2016-06-04 11:23:11	\N	2
10	3	activity.activity_status_changed	{"activity_id":3,"status":"Published"}	2016-06-04 11:23:15	2016-06-04 11:23:15	\N	2
11	2	activity.activity_status_changed	{"activity_id":5,"status":"Completed"}	2016-06-04 11:47:11	2016-06-04 11:47:11	\N	1
12	2	activity.activity_status_changed	{"activity_id":4,"status":"Completed"}	2016-06-04 11:47:19	2016-06-04 11:47:19	\N	1
13	1	activity.activity_status_changed	{"activity_id":3,"status":"Completed"}	2016-06-04 11:52:33	2016-06-04 11:52:33	\N	2
14	1	activity.activity_status_changed	{"activity_id":3,"status":"Verified"}	2016-06-04 11:52:45	2016-06-04 11:52:45	\N	2
15	1	activity.activity_status_changed	{"activity_id":3,"status":"Published"}	2016-06-04 11:52:54	2016-06-04 11:52:54	\N	2
16	1	admin.user_created	{"orgId":"1","userId":4}	2016-06-04 11:56:09	2016-06-04 11:56:09	\N	1
17	3	activity.activity_status_changed	{"activity_id":9,"status":"Completed"}	2016-06-05 05:40:01	2016-06-05 05:40:01	\N	2
18	3	activity.activity_status_changed	{"activity_id":9,"status":"Verified"}	2016-06-05 05:40:05	2016-06-05 05:40:05	\N	2
19	3	activity.activity_status_changed	{"activity_id":9,"status":"Published"}	2016-06-05 05:40:10	2016-06-05 05:40:10	\N	2
20	3	activity.activity_status_changed	{"activity_id":9,"status":"Completed"}	2016-06-05 06:02:30	2016-06-05 06:02:30	\N	2
21	3	activity.activity_status_changed	{"activity_id":9,"status":"Verified"}	2016-06-05 06:02:36	2016-06-05 06:02:36	\N	2
22	3	activity.activity_status_changed	{"activity_id":9,"status":"Published"}	2016-06-05 06:02:43	2016-06-05 06:02:43	\N	2
23	1	activity.activity_status_changed	{"activity_id":10,"status":"Completed"}	2016-06-05 06:30:50	2016-06-05 06:30:50	\N	1
24	1	activity.activity_status_changed	{"activity_id":10,"status":"Verified"}	2016-06-05 06:30:54	2016-06-05 06:30:54	\N	1
25	1	activity.activity_status_changed	{"activity_id":10,"status":"Published"}	2016-06-05 06:30:59	2016-06-05 06:30:59	\N	1
26	3	activity.activity_status_changed	{"activity_id":9,"status":"Completed"}	2016-06-05 07:11:23	2016-06-05 07:11:23	\N	2
27	3	activity.activity_status_changed	{"activity_id":9,"status":"Verified"}	2016-06-05 07:11:30	2016-06-05 07:11:30	\N	2
28	3	activity.activity_status_changed	{"activity_id":9,"status":"Published"}	2016-06-05 07:11:36	2016-06-05 07:11:36	\N	2
29	5	activity.activity_status_changed	{"activity_id":7,"status":"Completed"}	2016-06-05 07:20:31	2016-06-05 07:20:31	\N	3
30	5	activity.activity_status_changed	{"activity_id":7,"status":"Verified"}	2016-06-05 07:23:57	2016-06-05 07:23:57	\N	3
31	5	activity.activity_status_changed	{"activity_id":7,"status":"Published"}	2016-06-05 07:29:40	2016-06-05 07:29:40	\N	3
32	5	activity.activity_status_changed	{"activity_id":6,"status":"Completed"}	2016-06-05 09:09:28	2016-06-05 09:09:28	\N	3
33	5	activity.activity_status_changed	{"activity_id":6,"status":"Verified"}	2016-06-05 09:13:56	2016-06-05 09:13:56	\N	3
34	7	activity.activity_status_changed	{"activity_id":12,"status":"Completed"}	2016-06-05 12:10:35	2016-06-05 12:10:35	\N	5
35	7	activity.activity_status_changed	{"activity_id":12,"status":"Verified"}	2016-06-05 12:10:46	2016-06-05 12:10:46	\N	5
36	7	activity.activity_status_changed	{"activity_id":12,"status":"Published"}	2016-06-05 12:14:39	2016-06-05 12:14:39	\N	5
37	1	activity.organization_deleted	{"org_name":"Test organisation","super_admin":"yipl_admin"}	2016-06-05 12:48:10	2016-06-05 12:48:10	\N	3
38	1	activity.organization_deleted	{"org_name":"YoungInnovations","super_admin":"yipl_admin"}	2016-06-05 12:48:36	2016-06-05 12:48:36	\N	3
39	1	activity.organization_deleted	{"org_name":"T-1","super_admin":"yipl_admin"}	2016-06-05 12:48:41	2016-06-05 12:48:41	\N	3
40	1	activity.activity_status_changed	{"activity_id":8,"status":"Completed"}	2016-06-05 13:41:30	2016-06-05 13:41:30	\N	2
41	1	activity.activity_status_changed	{"activity_id":9,"status":"Completed"}	2016-06-05 13:41:37	2016-06-05 13:41:37	\N	2
42	1	activity.activity_status_changed	{"activity_id":9,"status":"Verified"}	2016-06-05 13:41:40	2016-06-05 13:41:40	\N	2
43	1	activity.activity_status_changed	{"activity_id":9,"status":"Published"}	2016-06-05 13:41:45	2016-06-05 13:41:45	\N	2
44	1	activity.activity_status_changed	{"activity_id":13,"status":"Completed"}	2016-06-05 13:42:24	2016-06-05 13:42:24	\N	2
45	1	activity.activity_status_changed	{"activity_id":13,"status":"Verified"}	2016-06-05 13:42:27	2016-06-05 13:42:27	\N	2
46	1	activity.activity_status_changed	{"activity_id":13,"status":"Published"}	2016-06-05 13:42:32	2016-06-05 13:42:32	\N	2
47	1	activity.activity_status_changed	{"activity_id":9,"status":"Completed"}	2016-06-05 13:45:54	2016-06-05 13:45:54	\N	2
48	1	activity.activity_status_changed	{"activity_id":9,"status":"Verified"}	2016-06-05 13:46:19	2016-06-05 13:46:19	\N	2
49	1	activity.activity_status_changed	{"activity_id":9,"status":"Published"}	2016-06-05 13:46:23	2016-06-05 13:46:23	\N	2
50	8	activity.activity_status_changed	{"activity_id":14,"status":"Completed"}	2016-06-06 03:10:39	2016-06-06 03:10:39	\N	6
51	8	activity.activity_status_changed	{"activity_id":14,"status":"Verified"}	2016-06-06 03:10:41	2016-06-06 03:10:41	\N	6
52	8	activity.activity_status_changed	{"activity_id":14,"status":"Completed"}	2016-06-06 03:11:37	2016-06-06 03:11:37	\N	6
53	8	activity.activity_status_changed	{"activity_id":14,"status":"Verified"}	2016-06-06 03:11:45	2016-06-06 03:11:45	\N	6
54	8	activity.activity_status_changed	{"activity_id":14,"status":"Published"}	2016-06-06 03:11:48	2016-06-06 03:11:48	\N	6
55	1	activity.organization_deleted	{"org_name":"YI Test","super_admin":"yipl_admin"}	2016-06-06 03:17:57	2016-06-06 03:17:57	\N	\N
56	10	activity.activity_status_changed	{"activity_id":15,"status":"Completed"}	2016-06-06 04:12:40	2016-06-06 04:12:40	\N	8
57	10	activity.activity_status_changed	{"activity_id":15,"status":"Verified"}	2016-06-06 04:12:42	2016-06-06 04:12:42	\N	8
58	10	activity.activity_status_changed	{"activity_id":16,"status":"Completed"}	2016-06-06 04:22:32	2016-06-06 04:22:32	\N	8
59	10	activity.activity_status_changed	{"activity_id":16,"status":"Verified"}	2016-06-06 04:22:34	2016-06-06 04:22:34	\N	8
60	10	activity.activity_status_changed	{"activity_id":16,"status":"Published"}	2016-06-06 04:23:00	2016-06-06 04:23:00	\N	8
61	10	activity.activity_status_changed	{"activity_id":17,"status":"Completed"}	2016-06-06 09:03:13	2016-06-06 09:03:13	\N	8
62	10	activity.activity_status_changed	{"activity_id":17,"status":"Verified"}	2016-06-06 09:03:15	2016-06-06 09:03:15	\N	8
63	10	activity.activity_status_changed	{"activity_id":17,"status":"Published"}	2016-06-06 09:03:19	2016-06-06 09:03:19	\N	8
64	1	activity.activity_status_changed	{"activity_id":19,"status":"Completed"}	2016-06-07 09:05:24	2016-06-07 09:05:24	\N	12
65	11	activity.activity_status_changed	{"activity_id":18,"status":"Completed"}	2016-06-07 09:06:23	2016-06-07 09:06:23	\N	9
66	1	activity.activity_status_changed	{"activity_id":19,"status":"Completed"}	2016-06-07 09:06:27	2016-06-07 09:06:27	\N	12
67	1	activity.activity_status_changed	{"activity_id":19,"status":"Verified"}	2016-06-07 09:06:34	2016-06-07 09:06:34	\N	12
68	16	activity.activity_status_changed	{"activity_id":20,"status":"Completed"}	2016-06-07 09:06:40	2016-06-07 09:06:40	\N	14
69	12	activity.activity_status_changed	{"activity_id":21,"status":"Completed"}	2016-06-07 09:06:54	2016-06-07 09:06:54	\N	10
70	16	activity.activity_status_changed	{"activity_id":20,"status":"Verified"}	2016-06-07 09:06:56	2016-06-07 09:06:56	\N	14
71	12	activity.activity_status_changed	{"activity_id":21,"status":"Verified"}	2016-06-07 09:06:58	2016-06-07 09:06:58	\N	10
72	1	activity.activity_status_changed	{"activity_id":19,"status":"Published"}	2016-06-07 09:06:59	2016-06-07 09:06:59	\N	12
73	12	activity.activity_status_changed	{"activity_id":21,"status":"Published"}	2016-06-07 09:07:07	2016-06-07 09:07:07	\N	10
74	13	activity.activity_status_changed	{"activity_id":22,"status":"Completed"}	2016-06-07 09:07:20	2016-06-07 09:07:20	\N	11
75	13	activity.activity_status_changed	{"activity_id":22,"status":"Verified"}	2016-06-07 09:07:31	2016-06-07 09:07:31	\N	11
76	16	activity.activity_status_changed	{"activity_id":20,"status":"Published"}	2016-06-07 09:07:35	2016-06-07 09:07:35	\N	14
77	13	activity.activity_status_changed	{"activity_id":22,"status":"Published"}	2016-06-07 09:08:37	2016-06-07 09:08:37	\N	11
78	11	activity.activity_status_changed	{"activity_id":18,"status":"Verified"}	2016-06-07 09:08:56	2016-06-07 09:08:56	\N	9
79	11	activity.activity_status_changed	{"activity_id":18,"status":"Published"}	2016-06-07 09:09:03	2016-06-07 09:09:03	\N	9
80	12	activity.activity_status_changed	{"activity_id":21,"status":"Completed"}	2016-06-07 09:09:56	2016-06-07 09:09:56	\N	10
81	12	activity.activity_status_changed	{"activity_id":21,"status":"Verified"}	2016-06-07 09:10:01	2016-06-07 09:10:01	\N	10
82	12	activity.activity_status_changed	{"activity_id":21,"status":"Published"}	2016-06-07 09:10:08	2016-06-07 09:10:08	\N	10
83	1	activity.activity_status_changed	{"activity_id":28,"status":"Completed"}	2016-06-08 12:28:42	2016-06-08 12:28:42	\N	2
84	1	activity.activity_status_changed	{"activity_id":28,"status":"Verified"}	2016-06-08 12:28:45	2016-06-08 12:28:45	\N	2
85	1	activity.activity_status_changed	{"activity_id":28,"status":"Completed"}	2016-06-08 12:29:48	2016-06-08 12:29:48	\N	2
86	1	activity.activity_status_changed	{"activity_id":28,"status":"Completed"}	2016-06-08 12:31:50	2016-06-08 12:31:50	\N	2
87	1	activity.activity_status_changed	{"activity_id":27,"status":"Completed"}	2016-06-08 12:40:31	2016-06-08 12:40:31	\N	2
88	1	activity.activity_status_changed	{"activity_id":27,"status":"Verified"}	2016-06-08 12:40:37	2016-06-08 12:40:37	\N	2
89	1	activity.activity_status_changed	{"activity_id":29,"status":"Completed"}	2016-06-08 12:47:20	2016-06-08 12:47:20	\N	16
90	1	activity.activity_status_changed	{"activity_id":29,"status":"Verified"}	2016-06-08 12:47:24	2016-06-08 12:47:24	\N	16
91	1	activity.activity_status_changed	{"activity_id":29,"status":"Published"}	2016-06-08 12:47:27	2016-06-08 12:47:27	\N	16
92	1	activity.activity_status_changed	{"activity_id":30,"status":"Completed"}	2016-06-08 12:48:29	2016-06-08 12:48:29	\N	16
93	1	activity.activity_status_changed	{"activity_id":30,"status":"Verified"}	2016-06-08 12:48:30	2016-06-08 12:48:30	\N	16
94	1	activity.activity_status_changed	{"activity_id":30,"status":"Published"}	2016-06-08 12:48:33	2016-06-08 12:48:33	\N	16
95	1	activity.activity_status_changed	{"activity_id":31,"status":"Completed"}	2016-06-08 12:50:28	2016-06-08 12:50:28	\N	16
96	1	activity.activity_status_changed	{"activity_id":31,"status":"Verified"}	2016-06-08 12:50:29	2016-06-08 12:50:29	\N	16
97	1	activity.activity_status_changed	{"activity_id":31,"status":"Published"}	2016-06-08 12:50:32	2016-06-08 12:50:32	\N	16
98	1	activity.activity_status_changed	{"activity_id":32,"status":"Completed"}	2016-06-08 12:52:59	2016-06-08 12:52:59	\N	16
99	1	activity.activity_status_changed	{"activity_id":32,"status":"Verified"}	2016-06-08 12:53:00	2016-06-08 12:53:00	\N	16
100	1	activity.activity_status_changed	{"activity_id":32,"status":"Published"}	2016-06-08 12:53:03	2016-06-08 12:53:03	\N	16
101	1	activity.activity_status_changed	{"activity_id":33,"status":"Completed"}	2016-06-08 12:54:32	2016-06-08 12:54:32	\N	16
102	1	activity.activity_status_changed	{"activity_id":33,"status":"Verified"}	2016-06-08 12:54:33	2016-06-08 12:54:33	\N	16
103	1	activity.activity_status_changed	{"activity_id":33,"status":"Published"}	2016-06-08 12:54:36	2016-06-08 12:54:36	\N	16
104	1	activity.activity_status_changed	{"activity_id":34,"status":"Completed"}	2016-06-08 12:55:29	2016-06-08 12:55:29	\N	16
105	1	activity.activity_status_changed	{"activity_id":34,"status":"Verified"}	2016-06-08 12:55:30	2016-06-08 12:55:30	\N	16
106	1	activity.activity_status_changed	{"activity_id":34,"status":"Published"}	2016-06-08 12:55:32	2016-06-08 12:55:32	\N	16
107	1	activity.activity_status_changed	{"activity_id":33,"status":"Completed"}	2016-06-09 04:52:28	2016-06-09 04:52:28	\N	16
108	1	activity.activity_status_changed	{"activity_id":33,"status":"Completed"}	2016-06-09 04:52:53	2016-06-09 04:52:53	\N	16
109	1	activity.activity_status_changed	{"activity_id":33,"status":"Verified"}	2016-06-09 04:52:56	2016-06-09 04:52:56	\N	16
110	1	activity.activity_status_changed	{"activity_id":33,"status":"Published"}	2016-06-09 04:52:58	2016-06-09 04:52:58	\N	16
111	1	activity.activity_status_changed	{"activity_id":34,"status":"Completed"}	2016-06-09 04:58:09	2016-06-09 04:58:09	\N	16
112	1	activity.activity_status_changed	{"activity_id":34,"status":"Verified"}	2016-06-09 04:58:11	2016-06-09 04:58:11	\N	16
113	1	activity.activity_status_changed	{"activity_id":34,"status":"Published"}	2016-06-09 04:58:14	2016-06-09 04:58:14	\N	16
114	1	activity.activity_status_changed	{"activity_id":35,"status":"Completed"}	2016-06-09 05:00:40	2016-06-09 05:00:40	\N	16
115	1	activity.activity_status_changed	{"activity_id":35,"status":"Completed"}	2016-06-09 05:00:58	2016-06-09 05:00:58	\N	16
116	1	admin.user_created	{"orgId":"2","userId":19}	2016-06-10 11:54:31	2016-06-10 11:54:31	\N	2
117	1	admin.user_deleted	{"orgId":"2","userId":"19"}	2016-06-10 11:54:34	2016-06-10 11:54:34	\N	2
\.


--
-- Name: user_activities_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('user_activities_id_seq', 117, true);


--
-- Data for Name: user_group; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY user_group (id, group_name, group_identifier, user_id, assigned_organizations, created_at, updated_at) FROM stdin;
\.


--
-- Name: user_group_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('user_group_id_seq', 1, false);


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY users (id, first_name, last_name, email, username, password, role_id, org_id, user_permission, time_zone_id, time_zone, remember_token, created_at, updated_at) FROM stdin;
6	fname	lname	anjesh+repoa@yipl.com.np	repoa_admin	$2y$10$SLiSiZync2iuZNfo3l6JnuF5P5o5fak97StSdu0KhKTjiOfz24r1q	1	4	\N	1	GMT	mjTQ428qJQXK7q3NxesvJvMHPpFjBinJIk5As2DnZfqFTYEb6bz6VRgy1S0g	2016-06-05 05:21:56	2016-06-05 05:28:13
9	Bibhusan	Bista	bibhusan@yipl.com.np	YITZ_admin	$2y$10$rVTbwB3Th8JdN7tjrYyQAO8vgtCUqEexubhOEZ2qJL7/QqsZyx8Ry	1	7	\N	1	GMT	\N	2016-06-06 04:01:32	2016-06-06 04:01:32
3	name1	last1	anjesh+fcs@yipl.com.np	fcs_admin	$2y$10$tkQzanXOm5vLkXX6v40XBeh/JuLLo6eALZipRPVHh6192kaxpVkMW	1	2	\N	1	GMT	V4meE7cvCyj3kfZYbI9HjKwSytK4Dtq8uHUw7TRkAhQkEuLMk0XrCEF4klbY	2016-06-04 11:15:24	2016-06-06 04:04:49
10	Bibhusan	Bista	bibhusan+yitz@yipl.com.np	yitz_admin	$2y$10$466kW1URS9MIHpf01NsYp.f0QZR5dCL9C61FSYpsL57EYeso8tvgC	1	8	\N	1	GMT	PwkT3x4WlCtjO51J0wjP6paGcXRPMnhJpXjIXxDCtx9h60esvA34ilUhYjmc	2016-06-06 04:05:58	2016-06-07 04:42:14
13	Oscar	Kimaro	oscar@restlessdevelopment.org	RD_admin	$2y$10$/3hbUpHnI1vImHrt4yozlu2Z1Dcqlb6Zru0Y.pHm0iEVEqBobv7Zi	1	11	\N	1	GMT	\N	2016-06-07 08:28:55	2016-06-07 08:28:55
12	Richard	Modest	rmodest@twaweza.org	TWA_admin	$2y$10$dMztDZlO2o4/pT6pCLTYVOAWfpfwNMVXZHwQobJvURFP/A08rgqCy	1	10	\N	1	GMT	kZOiqU1NrceBe8RxaYCVCU1rDRLUAhJXTEMjSYKUlbcKOKqg5Po2mRgufXEF	2016-06-07 08:28:41	2016-06-07 08:30:51
15	Haika	Mcharo	haikamcharo@yahoo.com	TCDD_admin	$2y$10$K/n77XYeJEj2NS6uOqblIOWsB.ikzRB6.5eOtelG7Q7oRgzKaVDlC	1	13	\N	1	GMT	\N	2016-06-07 08:37:04	2016-06-07 08:37:04
16	Sarah	Kiteleja	kidokittys@yahoo.com	Kido_admin	$2y$10$5dQLnekTeh.2HJeQTsJHseLovYuxcmTWdjbDdtkrBYKJi89VY0qt.	1	14	\N	1	GMT	\N	2016-06-07 08:37:08	2016-06-07 08:37:08
14	Bibhusan	Bista	bibhusan+tzdar@yipl.com.np	YI_admin	$2y$10$UUtbxS1Yx.TA85wPCLmuE.O15fJaxhA5CByF0qS./8mPCKDVfYjp.	1	12	\N	1	GMT	yBouNQ0CEV3ch3Oogz55ZKqi9xdoSMZlqRqAthXN29VmsmyWQRc6nbMZjUE9	2016-06-07 08:35:16	2016-06-07 09:10:53
17	robert	nyampiga	devoco@yahoo.com	deco_admin	$2y$10$cRrOsoqO0k1Q1mgeQA2q8eyU7yc0VEgnQU/2Wl8Ozbux933GhFmXK	1	15	\N	1	GMT	\N	2016-06-07 09:16:08	2016-06-07 09:16:08
11	Bartholomew	Mbiling'i	bmbilingi@thefoundation-tz.org	FCS_admin	$2y$10$eZPwBtJw0R3v6s1WSOXE6upRTAtGDKGxXA1NDyxcSAr30LlpPhB6a	1	9	\N	1	GMT	yPS5gkNsvUQc0sUtKPvkMU5hIzL6M9nTojweiNSZ7MdTfeBoiRb1pzVkVHBE	2016-06-07 08:26:10	2016-06-07 09:33:11
1	Yipl	Admin	admin@aidstream.com.np	yipl_admin	$2y$10$MuNZD8PmcImH0pP3ndBdn.WRh/oMdPOdCtQdd3lMr2nFGUaAODE.W	3	\N	\N	1	GMT	Ak23UsJ4jNpaVtJBWXLdfYa9iT5z9p0uW7GTddMIcqW7tIFrjgvOni5c6REj	2016-06-04 11:02:51	2016-06-08 12:43:09
18	sbs	sbs	sbs@asdf.com	ss_admin	$2y$10$/QdWW4xwJCH050N3AIC3a.un56dVlPJha3.LNRti7X.TTKWFRPR12	1	16	\N	1	GMT	\N	2016-06-08 12:44:10	2016-06-08 12:44:10
\.


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('users_id_seq', 19, true);


--
-- Data for Name: versions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY versions (id, version, created_at, updated_at) FROM stdin;
1	2.01	2015-09-01 11:42:29	2015-09-01 11:43:29
2	2.02	2015-09-01 11:42:29	2015-09-01 11:43:29
\.


--
-- Name: versions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('versions_id_seq', 1, false);


--
-- Name: activities_in_registry_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY activities_in_registry
    ADD CONSTRAINT activities_in_registry_pkey PRIMARY KEY (id);


--
-- Name: activity_data_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY activity_data
    ADD CONSTRAINT activity_data_pkey PRIMARY KEY (id);


--
-- Name: activity_document_links_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY activity_document_links
    ADD CONSTRAINT activity_document_links_pkey PRIMARY KEY (id);


--
-- Name: activity_published_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY activity_published
    ADD CONSTRAINT activity_published_pkey PRIMARY KEY (id);


--
-- Name: activity_results_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY activity_results
    ADD CONSTRAINT activity_results_pkey PRIMARY KEY (id);


--
-- Name: activity_transactions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY activity_transactions
    ADD CONSTRAINT activity_transactions_pkey PRIMARY KEY (id);


--
-- Name: documents_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY documents
    ADD CONSTRAINT documents_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: organization_data_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY organization_data
    ADD CONSTRAINT organization_data_pkey PRIMARY KEY (id);


--
-- Name: organization_published_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY organization_published
    ADD CONSTRAINT organization_published_pkey PRIMARY KEY (id);


--
-- Name: organizations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY organizations
    ADD CONSTRAINT organizations_pkey PRIMARY KEY (id);


--
-- Name: organizations_user_identifier_unique; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY organizations
    ADD CONSTRAINT organizations_user_identifier_unique UNIQUE (user_identifier);


--
-- Name: role_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY role
    ADD CONSTRAINT role_pkey PRIMARY KEY (id);


--
-- Name: settings_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY settings
    ADD CONSTRAINT settings_pkey PRIMARY KEY (id);


--
-- Name: user_activities_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY user_activities
    ADD CONSTRAINT user_activities_pkey PRIMARY KEY (id);


--
-- Name: user_group_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY user_group
    ADD CONSTRAINT user_group_pkey PRIMARY KEY (id);


--
-- Name: users_email_unique; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: users_username_unique; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_username_unique UNIQUE (username);


--
-- Name: versions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY versions
    ADD CONSTRAINT versions_pkey PRIMARY KEY (id);


--
-- Name: versions_version_unique; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY versions
    ADD CONSTRAINT versions_version_unique UNIQUE (version);


--
-- Name: jobs_queue_reserved_reserved_at_index; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX jobs_queue_reserved_reserved_at_index ON jobs USING btree (queue, reserved, reserved_at);


--
-- Name: password_resets_email_index; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX password_resets_email_index ON password_resets USING btree (email);


--
-- Name: password_resets_token_index; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX password_resets_token_index ON password_resets USING btree (token);


--
-- Name: activity_data_organization_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY activity_data
    ADD CONSTRAINT activity_data_organization_id_foreign FOREIGN KEY (organization_id) REFERENCES organizations(id) ON DELETE CASCADE;


--
-- Name: activity_document_links_activity_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY activity_document_links
    ADD CONSTRAINT activity_document_links_activity_id_foreign FOREIGN KEY (activity_id) REFERENCES activity_data(id) ON DELETE CASCADE;


--
-- Name: activity_published_organization_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY activity_published
    ADD CONSTRAINT activity_published_organization_id_foreign FOREIGN KEY (organization_id) REFERENCES organizations(id) ON DELETE CASCADE;


--
-- Name: activity_results_activity_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY activity_results
    ADD CONSTRAINT activity_results_activity_id_foreign FOREIGN KEY (activity_id) REFERENCES activity_data(id) ON DELETE CASCADE;


--
-- Name: activity_transactions_activity_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY activity_transactions
    ADD CONSTRAINT activity_transactions_activity_id_foreign FOREIGN KEY (activity_id) REFERENCES activity_data(id) ON DELETE CASCADE;


--
-- Name: documents_org_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY documents
    ADD CONSTRAINT documents_org_id_foreign FOREIGN KEY (org_id) REFERENCES organizations(id) ON DELETE CASCADE;


--
-- Name: organization_data_organization_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY organization_data
    ADD CONSTRAINT organization_data_organization_id_foreign FOREIGN KEY (organization_id) REFERENCES organizations(id) ON DELETE CASCADE;


--
-- Name: organization_published_organization_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY organization_published
    ADD CONSTRAINT organization_published_organization_id_foreign FOREIGN KEY (organization_id) REFERENCES organizations(id) ON DELETE CASCADE;


--
-- Name: settings_organization_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY settings
    ADD CONSTRAINT settings_organization_id_foreign FOREIGN KEY (organization_id) REFERENCES organizations(id) ON DELETE CASCADE;


--
-- Name: user_group_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY user_group
    ADD CONSTRAINT user_group_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;


--
-- Name: users_org_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_org_id_foreign FOREIGN KEY (org_id) REFERENCES organizations(id) ON DELETE CASCADE;


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

