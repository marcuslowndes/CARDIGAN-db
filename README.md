---
Title: Database Web Application for the CARDIGAN Project
Module: BSc Honours Project Report
Author: Marcus Lowndes
Date: 17/07/2020
---

# Abstract

The CARDIGAN project aims to analyse patterns in gait with relation to diabetes in teenagers. This involves a clinical trial in which clinical information about teenage healthy and diabetic patients are taken, alongside a series of gait tests, on a weekly basis. The large amount of clinical data and data produced in tests is stored in spreadsheets. This is impractical and inaccessible to the global scientific community, so a web-based database system was developed using web technologies to store and host the data online. This allows easier access to data for the project researchers, and provides open access to the data for future analyses by the global scientific community.

**Keywords:** Data management; clinical trials; CARDIGAN project; EAV Model; RDB

# Introduction

Type 2 diabetes (T2D) is a metabolic disorder characterised by insulin resistance of cells, which results in high blood sugar levels and frequently in an underproduction of insulin. The cause of T2D is primarily obesity and a lack of exercise, though there are also some genetic factors (World Health Organisation, 2020). There are many acute and chronic symptoms of the condition that reduce the patient's quality of life. The treatment for T2D mainly involves lifestyle changes, such as dietary changes and increased exercise.

Possibly due to accessibility of unhealthy food and more sedentary lifestyles prevalent in modern-day living, diabetes rates across the world have been rising steadily since 1960, in parallel with obesity (Moscou, 2013). In 2016, it was found that in Mexico, almost one in ten adult Mexicans had some form of diabetes (Campos *et al.*, 2020).

The Criticality Analysis of Diabetic Gait in Children (CARDIGAN) project is a study that aims to identify patterns in the relationships between gait and the risk of developing diabetes. In doing so, it contributes to the ongoing research of how diabetes gives rise to peripheral nervous system pathology that results in changes to gait (Alam *et al.* 2017).
The *Criticality Analysis of Gait* is a novel machine learning approach built upon the Rate Control of Chaos model developed in 2017 by T. olde Scheper, and is designed to observe and control complex, chaotic systems. The data analysis portion of the study is conducted by the School of Engineering, Computing and Mathematics at Oxford Brookes University, Oxford, UK.
The study consisted of a set of community-based clinical trials on children, conducted by the Hospital Infantil de Mexico Federico Gomez (HIMFG), Mexico City, Mexico. In this study, gait data and clinical data was collected on a weekly basis, with an aim towards potentially developing an analysis of these data as a detection, prevention and management tool for diabetes.

The study has produced a sizable amount of clinical, questionnaire, and gait data, and the researchers involved currently have a system in place for manually storing these data using spreadsheets stored on the *Google Drive* cloud storage platform.
Clinical data took the form of survey data (including psychological and nutritional information), biochemical measurements, and physical measurements of activity (from an wrist-bound activity monitor), while gait data took the form of inertial and positional data produced by an Inertial Measurement Unit (IMU) attached to the patient's abdomen.
Each weekly visitation has separate spreadsheets for clinical and gait data. This amount of data is difficult to manage in its current form, since if the changes in a data item of interest are to be evaluated throughout the study, the results must be manually obtained from each of these visitation spreadsheets.

This system has been in place since the start of the project and as a result of the rapid increase in data as the study progresses, is proving to be cumbersome, and organising it is uses up much of the researcher’s time.
The spreadsheets are also only accessed by researchers involved in the CARDIGAN project, however the researchers require a way to publish the data for the wider scientific community to access, for future analyses.

The purpose of this document is to report on the development of an website for the CARDIGAN project, including an online database solution for hosting and sharing the results of the study.
The initial plans discussed by the project supervisor for the database application were to develop the back-end database using the electronic data capture (EDC) application REDCap to store the data, and a web front-end to display it.
However, upon inspection of the tools provided by REDCap, it was quickly discovered that, despite requiring a web server with PHP, and MySQL database server, it does not support any form of relational database modelling by the developer.
REDCap is an application that provides tools to support workflow-based data collection projects, with a primary focus on creating, distributing and capturing data for surveys (Patridge and Bardyn, 2018).
Hence, REDCap would have been the perfect solution for the CARDIGAN project’s clinical and questionnaire data capture requirements, if the project team were using it to create their questionnaires and begin capturing data at the beginning of the project.

However this was not done by the team, and most of the data is now already captured and stored in spreadsheets on cloud storage.
Because of REDCap’s workflow, the questionnaire would have to be recreated, and the data that is already captured would have to be imported into it.
This was deemed by the developer to be beyond the scope of the project, so an alternate solution was researched. It was decided to implement a new database system using more flexible tools, that the developer could shape to fit with the project's goals.

---

# Aims and Objectives

Project Aim: | To develop a database management system for the clinical and gait data sets from the CARDIGAN project that can be accessed online via a web application.
-|-

## Objectives

1. Complete a literature review of previous implementations of online clinical databases.
2. Design and implement a relational database based on the provided clinical and gait data sets
3. Research appropriate methods to model the database
4. Design an online web-based system to access and manage the database
5. Implement the database application using suitable web technologies

## Stakeholders

Role | Name
-|-
Project supervisor: | Arantza Aldea
Developer and project leader:| Marcus Lowndes
Main stakeholders: |CARDIGAN Team
Stakeholder and CARDIGAN team liaison: |Mireya Munoz Balbontin

---

# Literature Review

Research was conducted on other forms of database software that is relevant to clinical studies. It is not a common software market segment and few vendors of solutions exist. The associated area of clinical trials does have a commercial market of solutions:
- Aris Global: https://www.arisglobal.com/
- Castor EDC: https://www.castoredc.com/clinical-data-management-system/
- Clinical Conductor CTMS: https://go.bio-optronics.com/clinical-conductor-capterra
- EDGE: https://info.gartnerdigitalmarkets.com/edge-gdm-lp
- Ripple: https://ripplescience.com/clinicaltrials/
- Caspio: https://go.caspio.com/clinical-trial-management-06252020
- DFdiscover: https://info.gartnerdigitalmarkets.com/dfnetresearch-gdm-lp/

And there is also an open source provider:
- OpenClinica: https://www.openclinica.com/

However much like REDCap, these solutions almost entirely focus on EDC -- and analytics and visualisation are primarily focussed on determination of drug trial success, which is not the scope of this project. After consideration, these were not considered relevant to the current project.

Certain types of data, such as from a questionnaire or survey, clinical study, or medical data are typically highly sparse (Dinu and Nadkarni, 2007). There are often many missing, or `NULL` values in the data, often due to a subject not answering a given question, or not turning up to a visitation to record information. A data schema needs to be flexible to this requirement. Also, these data are highly heterogeneous (Löper *et al.* 2013); there are many different, dissimilar attributes that could either be modelled all in the same entity, or each require a separate table with composite primary keys to represent the multiple weekly instances of that attribute.

This creates a model with either a couple of extremely large entities with an attribute for every question, or an extremely large and complicated model with a composite table for every single attribute. This is one of the main challenges for modelling a clinical/questionnaire-based database for the CARDIGAN project data. To address this complexity, research into data prepresentation was carried out.

The Entity-Attribute-Value Model is a generic design for relational database models that abstracts entities, attributes and values of a conventional model into tables for each. As shown in Figure 1, this simplifies into 3 main tables, an Entity table, an Attribute table, and a Value tale. This provides many advantages relevant to a database for weekly questionnaire data such as this. For example, the sparseness of the data will no longer be an inefficient use of storage because a value that would be stored as NULL in a conventional database is simply not stored at all in an EAV-based one (Nadkarni *et al.* 1999).

![EAV Model](2-2-eav-model.png)

***Figure 1:*** *The tree main tables that comprise the EAV Model.*

Another major benefit of the EAV model is that it is flexible and offers the opportunity to expand on the model, even after it is deployed. Because entities and attributes are stored as values, new ones can be added after deployment without changing the model of the database itself. This provides more flexibility for questionnaires and clinical data capture, as the researchers may intend to ask new questions during a visitation after the new storage system has been deployed. This allows them to add their own attributes and entities to their data storage model without needing to change the core RDB model (Anhøj, 2003).

In implementation, the EAV model does not actually consist of only 3 tables, there in fact should be a separate value table for each value type (e.g. a `Value_Boolean` table and a `Value_Integer` table), due to the restriction of one value type per Value attribute. It is also not a strictly enforced design, and can be molded to suit the context and requirements. For example, you may have a hybrid model, with some data and metadata stored in conventional tables, and specific data stored in an EAV Model (Löper *et al.* 2013). An example of this is provided in Figure 2, below.

![Enhanced EAV Model](2-2-enhanced-model.jpeg)

***Figure 2:*** *An example of an* enhanced *EAV-based model, including EAV tables, metadata tables, and a conventional table. This example also includes separate value tables for each data type. This is the basic model for the generic schema used in a study by Löper,* et al. *in 2013, and is taken from that study.*

Despite the simplicity of the EAV model from a database design perspective, the data retrieval aspects of the implementation are actually far more complex. The greatest disadvantage of the EAV model comes from the lack of efficiency in data retrieval, and for larger databases can be significantly lower performance and hence be slower (Nadkarni *et al.* 1999). Since the aim of this project is to produce a website that is not accessed by the public, but is instead restricted to the scientific community, the database does not need to be designed with much scalability in mind, so performance is not a major concern for the developer. Indeed, if performance were to become an issue, potential mitigations would include denormalisation into common data views that could be indexed or cached for fast retrieval.

Since these disadvantages seem irrelevant in the context of the CARDIGAN project's data sets, so it was decided that EAV was the best choice of model design.

---

# Proposal

## Current System

As specified in Section 1, the current system presents with spreadsheets that are stored on the Google Cloud platform. Data is collected by multiple methods and processed by the research team before it is recorded digitally using Microsoft Excel. The method by which data is collected depends on the part of the study that is taking place. On each week of the study, each patient filled out a survey and had some biochemical and cardiovascular information recorded. These patients are also asked to do some physical exercise each week and record information about it. This data is considered to be within the "Clinical" data set.

The patients then partook in two on-site activities at HIMFG that involved walking while wearing the IMU device: One activity where they walked up and down a track continuously for 6 minutes, and another activity that involved walking up and then down a 10 metre track once over. This data is considered to be within the "Gait" data set. The physical and cardiovascular measurements from this activity were recorded from a wrist-bound activity monitor and positional and inertial measurements were recorded by the IMU device.

All the data for each week was stored in a separate Microsoft Excel spreadsheet accessed via the Google Drive cloud storage platform, wherein each data set has a separate directly, and each week's data was stored in a separate spreadsheet. The gait data was further subdivided into 6-minute activity and 10-metre activity subsets.

These subsets were further divided: the 6-minute subset was divided into *beginning*, *middle* and *end* subsets, and the 10-metre subset was divided into an *up* subset and a *down* subset. Any researcher who wishes to analyse this data will need to search through each spreadsheet and manually extract the columns for any data item(s) of interest.

## Proposed System

The proposed system aims to provide a web-based database solution to streamline this process of extracting data items of interest. It takes the form of a bespoke website that can also function to provide information or promotional content about the CARDIGAN project to the user.

This website will provide access to a database system that hosts the data from the study and allows verified researchers to access the data for appraisal, or for their own research and analysis. The system will allow them to select multiple data items, and view and download the data for all patients, across all visitations.

The system will also provide an appropriate and secure user account system, including permission levels that prevent unverified accounts from accessing the database. The full requirements of the proposed system are detailed in the next section.

---

# Requirements Analysis

## Requirements

The requirements, as elucidated after multiple discussions with the project supervisor and the CARDIGAN Team liaison, were determined as follows:

Number | High-Priority Functional Requirements
-|-
**FR1.** | Develop a user account system. Users will be able to register and log in, manage their password and delete their account.
**FR2.** | User access control must be implemented with three levels of privilege. A user always registers as the lowest privilege level - as an *unverified* user. The next privilege level is *verified*; a *verified* user can access the database application. The highest privilege level is *admin*.
**FR3.** | Any *admin* can view and manage other users’ privilege levels. It is their responsibility to *verify* new users.
**FR4.** | Develop a suitable relational database solution for the clinical and gait data.
**FR5.** | Develop a web application that accesses the database and display results from the database onto the screen.
**FR6**. | Develop a browse or filter function that allows the user to select one or more individual data items from the clinical and/or gait data sets, and returns the results across each visitation.
**FR7.** | Implement a function to allow a user to export the results to a CSV file that can be downloaded and used for analysis.

Number | Low-Priority Functional Requirements
-|-
**FR8.** | A functional contact form on the website that will send an email directly to the admin of the website.
**FR9.** | Some content and promotional material for the website that explains the CARDIGAN project to the audience and displays news and events. This should include a *welcome* page, an *about* page, and a photo gallery.
**FR9.** | **(Optional)** A content management system, giving an admin of the website the ability to create news or blog entries, add photos to a photo gallery and set up events. This is the lowest priority requirement, as it may add extra development time that could be spent on the high-priority requirements.

Number | Non-Functional Requirements
-|-
**NFR1.** | Ensure user accounts are implemented with appropriate security features, including password encryption and protection from attacks such as SQL injection.
**NFR2.** | Implement the entire application system using the MVC pattern for improved development workflow, efficiency and security.
**NFR3.** | Implement the database using an EAV-based model for database structure simplicity and flexibility.
**NFR4.** | Ensure the website is presented with a clean, professional design, and has support for responsive web design, such that it is viewable on mobile devices.

### Use Cases

The use cases of the system are given below:

![Use Cases](3-use_cases.svg)

***Figure 3:*** *The use cases given are for the different types of user that will interact with the system.*

---

# Professional Issues

As with any service or system, there is legislation in place to protect users. It is required that the new system is developed in accordance with these laws, with the security of the customer’s personal data in mind. Some of these laws are described below. As such, all clinical data has been anonymised prior to entering the system. To do this, any personally identifying information within the data such as name, address, contact details, etc. have been removed prior to storage in either the spreadsheets or the database. This enables the anonymous data to be available publicly without any risk of a patient’s personal data being breached.

Throughout the process of the project, there are some regulations that are relevant to development of the application. The Computer Misuse Act 1990 is a preventative measure against intentional malicious use of computers. Specifically, it criminalises unauthorised access or modification of any program or data on a computer, with an emphasis on criminal intent. (Scottish Qualifications Authority, 2008) This piece of legislation helps protect the developers in the workplace.

The EU General Data Protection Regulation (GDPR) is an EU regulation that is intended to set a standard for the EU to follow regarding data protection of users/customers. It applies to all companies processing the data of users regardless of location. The regulation incurs penalties for companies who do not comply with its requirements for them to provide proper avenues for customer consent when requesting to use customer data, and properly detailing how that data will be used (Trunomi, 2018).

---

# Design Specification

## Database Model

The data sets produced by the CARDIGAN project's clinical study are diverse in terms of type and purpose. For example, a some of the clinical data set is made up of questionnaire questions, or biochemical measurements, while the entire gait data set is made up of inertial and positional measurements made by the IMU.

As shown by the data sets described in *Section 2.3.*, the Gait data sets have subsets to represent categorisation of the data, which needs to be represented in the model, by table structure or metadata. The Clinical data was also split into non-functional subsets for the sake of categorisation. The subsets to both the Clinical and Gait data sets are shown in Figure 4, below.

![Data Sets and Subsets](5-1-data-catergories.svg)

***Figure 4:*** *The data sets, subsets and categories of all the CARDIGAN project data.*

Due to the heterogeneity of the individual data items, and the categorisation and structure of those data: every data item and column name in the database are the same, from a functional perspective. That is to say, there are no relationships between any of the data items -- they are all independent despite their different categories and metadata. This means that traditional methods of relational database design, such as normalisation, proved to be impractical.

This can be seen in Figure 5, showing a possible interpretation of the model. Figure 5, all the data items are arranged as attributes in a couple of a couple of very large tables, with the Patient ID and week number as additional metadata attributes. However, Figure 6 suggests an interpretation where the data items are arranged as columns in smaller tables, functioning purely as categories.

![Interpretation A](5-1-interpretation-1.png)

***Figure 5:*** *An interpretation of the theoretical relational model, where the data is represented in these very large, complicated tables. The data would be very sparse in these tables.*

![Interpretation B](5-1-interpretation-2.png)

***Figure 6:*** *An attempt to derive some smaller tables from these data attributes. There are no relationships between any of these tables and the different tables only function to categorise the attributes, and not represent any relationships.*

Both of these interpreted models are unnecessarily big and cumbersome, due to the large number of attributes that represent only heterogeneous data values with no relationships, that serve no function within the database other than storage and categorisation, and these data will are also quite sparse. The former interpreted model (Figure 5) is especially cumbersome due to the long table with so many attributes; the data is uncategorised, but the relationships are more represented. The latter model is also cumbersome because the separation into smaller entities is purely arbitrary, and adds complexity to the model, along with redundancy of needing repeated `Week ID` and `Patient ID` foreign keys to function.

Figure 7 shows an earnest attempt at normalisation. It can be observed that some of these data items occur only once per patient, but some values occur additionally for every visitation that the patient attended. In this model, data from the pre-consultation that is not recurring has it's own table, whereas every single attribute that occurs on multiple visitations would need a separate entity with two primary keys, to allow a joining any number of results for that patient for each week that the result occurred.

![Interpretation C](5-1-interpretation-3.png)

***Figure 7:*** *An earnest attempt to start normalising the data. Since the data is so heterogenous and there very few relationships to represent, it was considered unnecessary to continue.*

None of these attempts to normalise the data produced anything resembling a suitable and practical model for the database. Therefore, the research process described in Section 2.2. was employed, and as a result the Entity-Attribute-Value model was explored as a solution. The resulting model is shown in Figure 8, below.

![Entity Relationship Model](5-1-cardigan-model-final.svg)

***Figure 8:*** *After considering the research on the EAV-based schema, this model was developed to represent the relationships between the clinical study data, it's metadata, and conventional data.*

The model, as described further in this chapter, was developed with inspiration from the model designed by Löper, et al., 2013, which employed a hybrid of the EAV model and a conventional relational model, by representing certain metadata outside of the EAV system.

By observing the structure of the data stored in the spreadsheets, specifically that each column could be considered as a *data item* (excluding ones labelled *Patient ID*) : A separate `attribute` could be employed to describe each one, while anything else (including the *Patient ID*) could be regarded as some sort of metadata, including data sets, categories and *Week IDs*.
In this sense, each of the data items were assigned an `attribute.Name`, and an `attribute.Value_Type` to describe their MySQL data type. The values that occur within each cell of the spreadsheets are stored in a `value_<MySQL_data_type>` table corresponding to the MySQL data type of that value's column (*data item*).

The `value` table functions to describe each individual value in the database, by providing the relationships between the `value_<MySQL_data_type>` and it's metadata, including it's `entity`, `attribute`, `visitation` (the week that the data is from - represented by it's own table) and `patient` (the participant of the study - also represented by it's own table).
Thus, the `entity` table functions to categorise the individual *data items*/`attribute`s so that the `attribute`s that occur more than once for each patient (e.g. on a weekly basis) can be represented as separate, while also having the same `attribute.Name`.
A higher level of categorisation is represented by the `data_type` table. This table provides metadata for the `entities`: Information regarding the data set (*clinical*/*gait*), and the subsets as described in *figure-data-categories*, previously.

As shown in Figure 8, the database also contains user data, for the log-in and user access control system, all stored in a single `user` table. This data has no relationship with the patient data, so no relationships are represented between the user table and the rest of the database, although this may change if additional functions are added to the website in the future, such as content management.

---

## Object Model


### Design Patterns

Outside from the database itself, a peripheral system was developed to allow access to the database and prevent unwanted users from accessing it. This took the form of a web application with open access to certain content, user logins, user access control, and a way for verified users to view data that they had specified.

It was decided at an early stage to use Object-Oriented (OO) design principles to develop the application, and to create a cleaner, more professional and more elegant solution for the system. More specifically, it was decided to take advantage of a framework that employs the Model-View-Controller (MVC) design pattern, which is a  popular design pattern for Graphical User Interface (GUI) applications.

The main principle of MVC is to separate the user interface from the underlying data model that is being represented (Leff and Rayfield, 2001). It achieves this by separating the class that constructs the GUI as the *View*, from the class that represents and controls the core data of the system; the *Model*.

The View can only read from the Model but cannot change or manipulate it, so any input from the user is handled by an intermediary class called the *Controller*, which is reserved for taking input from the user, formatting that input into a form that can be understood by the Model, and communicating directly with the Model.

The View can also communicate with the Controller to request reformatted data from the Model that can be understood by the View and/or the user. This process is represented in *figure* below.

![Model-View-Controller](5-2-mvc.png)

***Figure 9:*** *A diagram describing the MVC process.*

There are many benefits to using the MVC pattern, for example any changes to the View and/or the Controller do not affect the Model in any way; the core data model will be left unaffected. To extend, a variety of different Views could be constructed to use the data from the same Model, which leaves the data model unchanged but allows for many different representations of the data to be easily implemented in a modular fashion. The application will have multiple Models, Views and Controllers due to the many functions it will perform.


### Controllers

The Controller classes define the 3 main functions of the application:

1.  To provide some simple content to the user, including at minimum, static "welcome" and "about us" web pages.

2.  An account system that at minimum allows users to register, log in, manage their account, and gain access to parts of the website based on their account's permission level.

3.  A system that allows a verified user to view and download the data from the CARDIGAN project.

4.  **(Optional)** A content management system that provides functions to present content to the view, and allow admins to create, upload and maintain new content. This content can take the form of text or images.

Each of these functions is handled by a single Controller class.


### Models

The Model classes will define access and management of the (at minimum) 2 independent database models that are used in the system:


1.  Accesses the `user` table of the database. This handles all requests for data about the user accounts. It covers the following:
    - Check to see if an email is an duplicate
    - Register a new user
    - Check to see if a password is valid
    - Timestamp a successful login
    - Retrieve user information


2.  Access all database tables associated with the EAV model. This class is responsible for retrieving any data from the CARDIGAN project for the View. This includes:
    - Retrieving data *subsets* from a data set
    - Retrieving data *categories* (entities) from a subset of data
    - Retrieving *data items* (attributes) from a selected entity
    - Retrieving all values, alongside relevant metadata (such as `patient` data and `visitation` data) for the chosen *data item*.


3.  **(Optional)** A model for storing, managing and presenting content such as gallery photos, blog posts and event announcements. Only admins can create and edit this content. This includes:
    - Retrieving content from the database given certain parameters, such as time added or relevancy.
    - Changing or editing content that is already stored in the database.
    - Adding new content to the database.


### Views

The View refers to only the HTML code and content that is viewed by the user. Several templates written in HTML and PHP are used by the Controller to build the current View, depending on the page of the website that the user has accessed.

These templates will be constructed in a fashion such similar to the following:
1.  The header and navbar for each webpage.
2.  The content of some static pages, e.g. the content of the Welcome page of the website, or the Login/Registration form.
3.  The footer of the webpage.

Some parts of the website can be constructed by using smaller template components. For example a small template can be made that describes a single part of a page that contains content, and when the page is reloaded, that template can be swapped for a different template, depending on session data.

---

## GUI Design

The layout of the user interface of the application is represented with the following diagrams:

#### Welcome Page
![Welcome Page](5-3-site-map/layouts-Welcome.svg)

#### About Page
![About Page](5-3-site-map/layouts-About.svg)

#### Gallery Page
![Gallery Page](5-3-site-map/layouts-Gallery.svg)

#### Contact Us Page
![Contact Us Page](5-3-site-map/layouts-Contact-Us.svg)

#### Log In Page
![Log In Page](5-3-site-map/layouts-Log-In.svg)

#### Register Page
![Register Page](5-3-site-map/layouts-Register.svg)

#### Manage Account Page
![Manage Account Page](5-3-site-map/layouts-Manage-Account.svg)

#### All Users Page
![All Users Page](5-3-site-map/layouts-All-Users.svg)

#### Database Search Page
![Database Search Page](5-3-site-map/layouts-Database-Search.svg)

#### Database Result Page
![Database Result Page](5-3-site-map/layouts-Database-Result.svg)

---

# Implementation

## Management / Organisation

### Planning

The project was initially planned using the Gantt chart editor ProjectLibre, by following the *waterfall* method of software development. This is provided in Figure 10. However, it was found that of the *waterfall* method was not a suitable model, due to issues that arose, preventing the original plan from seeing success. Therefore a more adaptable method had to be used such as the SCRUM method.

Throughout mostly the implementation phase of the project, most short-term tasks were planned and tracked using the simple list app Microsoft To Do. Any changed made to file structure and code within the projects development environment were tracked and recorded using Git.

![Gantt Chart 1](6-1-gantt-1.png)

![Gantt Chart 2](6-1-gantt-2.png)

![Gantt Chart 3](6-1-gantt-3.png)

![Gantt Chart 4](6-1-gantt-4.png)

***Figure 10:*** *The original project plan that the developer intended to follow. The table below provides a key.*

ID|	Name|	Duration|	Start|	Finish|	Pre
-|-|-|-|-|-
1|	Database Web Application for the CARDIGAN Project|	138 days|	07/10/19 08:00|	15/04/20 17:00
2|	Proposal|	15 days|	07/10/19 08:00	|25/10/19 17:00
3|	Initial Meeting with Project Supervisor/ Project Initiation|	0.062 days|	07/10/19 13:30|	07/10/19 14:00
4|	Project Plan|	15 days|	07/10/19 08:00	|25/10/19 17:00
5	|Literature Search|	15 days	07/10/19 08:00|	25/10/19 17:00
6|	Meeting with CARDIGAN project team contact|	0.125 days|	11/10/19 10:30|	11/10/19 11:30
7|	Requirements and Design Methodology	|19.875 days|	28/10/19 09:00	|22/11/19 17:00
8|	Requirements Specification|	9.875 days|	28/10/19 09:00|	08/11/19 17:00|	4;5
9	|Data Model	|9.875 days	|28/10/19 09:00	|08/11/19 17:00|	4;5
10|	Website Design and Structure|	9.875 days|	11/11/19 09:00|	22/11/19 17:00|	8;9
11	|Test Plans|	9.875 days|	11/11/19 09:00|	22/11/19 17:00|	8;9
12|	Introduction and Lit Review|	9.875 days|	18/11/19 09:00|	29/11/19 17:00
13	|Implementation	|44.875 days	|02/12/19 09:00	|31/01/20 17:00
14|	Implement Data Model in REDCap|	14.875 days|	02/12/19 09:00	|20/12/19 17:00|	12
15|	Implement Website Front End Structure and basic styles|	14.875 days	|16/12/19 09:00|	03/01/20 17:00
16|	Implement Website Functional Requirements|	24.875 days|	30/12/19 09:00|	31/01/20 17:00
17|	Final Report|	34.875 days|	03/02/20 09:00	|20/03/20 17:00|	16
18|	Project Poster|	24.875 days	|02/03/20 09:00|	03/04/20 17:00
19|	Demonstrate Poster and Software|	1 day|	15/04/20 08:00|	15/04/20 17:00|	18

***Table to provide a Key to Figure 10.***

### Risk Serverity Matrix

 - | Negligable | Minor | Moderate | Serious | Critical
-|-|-|-|-|-
81-100 | | |  | Not every high-priority requirement is implemented |
61-80 | | | Not every low-priority requirement is implemented | Hardware failure |
41-60 | | | | Project overruns deadline | Data loss, Software failure
21-40 |  | | Little or no communication between the developer and the stakeholders | |
0-20 | Data is not fully translated into English by the CARDIGAN Team

---

## Software and Tools

A number of technologies and different software packages have been used in development of the web application. They are described in following two categories:

### Development
- Wampserver64 3.2
    - An intuitive Windows development environment that includes support for the latest releases of Apache, MySQL and PHP. Used in the development phase of the project to host a local server for testing the application.


- MySQL Workbench 8
    - A powerful application that has tools for modelling databases visually, and forward engineering SQL code that can be imported into an RDBMS (RDB Management System) to form the structure of the database. Used to initially model the database.


- Sublime Text 3, Notepad++, Atom
    - Lightweight text editors used mostly in this project for coding PHP, HTML, CSS and a little JS.


- Python 3.7
    - An interpreted Object-Oriented Programming (OOP) language that is easy to code for, and due to it’s dynamic typing and support for procedural programming, is perfect for programming algorithms, so was used to develop an algorithm to convert the clinical data.


- PyCharm Community
    - An intelligent IDE that includes many quality-of-life features for coding in Python, such as syntax highlighting and code completion, used to develop the data conversion algorithm in Python (see Section 4).


### Implementation

- PHP 7.3.12
    - The primary programming language used to develop the web application.


- MySQL 8.0.18
    - The database server used to store the CARDIGAN project clinical and gait data.


- Apache 2.4.4.1
    - The web server software used to host the website and process HTTP requests.


- phpMyAdmin 4.9.2
    - The administration tool used by the developer to implement the database model and initialise and populate the database.


- CodeIgniter 3.1.1
    - The Model-View-Controller (MVC) framework used to securely and efficiently develop the web application.


- Bootstrap 4.4.1
    - A CSS framework used to quickly and efficiently provide clean, functional, responsive styles so the developer can focus more on the functionality of the website during early development.


- jQuery 3.1.1
    - A JavaScript library used by Bootstrap for various functionality, such as responsive web design.

---

## DB Implementation

As the design of the database was established, the model presented in Figure 8 was built using MySQL Workbench. The model was forward engineered to produce a MySQL script which was then run on the local MySQL server (by importing it into phpMyAdmin) to build the database structure.

As described in Section 5.1., each data item was taken from the original spreadsheet table columns and then categorised into attributes and entities, to fit the new model. Comma-Separated-Values (CSV - a form of plain-text spreadsheet) files were then manually written to populate the `entity` and `attribute` tables, including the `data_types` table, which  as described in *Section 5.1* was written to contain additional metadata to give extra categorisation for entities.

After the model portion of the database was populated, including all the `entity`s, `attribute`s and metadata, it came time to populate the database with the values. This included all the individual values from each spreadsheet.
This was a complex task that required data values to be sorted, validated, reformatted and then assigned foreign keys to describe each value's relationship with it's `idEntity`, `idAttribute` and metadata (`idWeek` and `idPatient`).
Due to different people manually recording the original data values by hand each week, those values did not always follow a standardised format, and contained some spelling mistakes. The sheer volume of data also prevented it from being sorted and formatted manually, as this would have taken an unreasonable amount of time.

As a result of these issues, an algorithm was written using the Python programming language that reads each of the spreadsheets, validates each value, and organises, reformats and writes each value as a row in two CSV files, the details of which are explained further down. The algorithm is made up of functions in the `cardigan_data_extract` Python module. To be read and understood by Python, the Microsoft Excel spreadsheets were converted into CSV format.

For each data set (Clinical and Gait), a block of code in the `cardigan_db_relationships` module is dedicated to delivering an array that describes the relationships between the column name in the raw data file, with the `idAttribute`, `idEntity`, `attribute.Name` and MySQL data type (`attribute.Value_Type`).

For each data set, a short script was then written that uses both these aforementioned modules to reformat the data for immediate upload into the database using phpMyAdmin.
Each script begins by manually setting the `currentValueId` to the numerical value lowest available `value.idValue` shown in phpMyAdmin. The name of the directory containing the data set is provided in the `dirName` value, and the `relationships` value is given the appropriate array from the `cardigan_db_relationships` module. Currently these values are hard-coded to the script.
The script will then run through each week's spreadsheets, and run the `extractData` and `writeCSVFiles` functions provided by the `cardigan_data_extract` module. The data is only input as a CSV file, where each patient is in alphanumerical order.

The `extractData` function runs through each cell of the CSV file, and returns an associative array (a Python `dictionary`) that sorts each value according to it's MySQL data type (using the `relationships` array), into an array containing the ID numbers and the value itself.

The `writeCSVFiles` function is then run on that array to write the values of each data type to CSV files. One file contains all the relevant ID numbers of metadata columns, to match the attributes of the `value` table. The other files contain the value itself (alongside it's `idValue` foreign key), where that file is specific to the MySQL data type of the value.
For example, a script accessing a spreadsheet containing only data of the `DECIMAL` MySQL data type (such as Gait data) will only output `value.csv` file and a `DECIMAL.csv` file. Each row of the `value.csv` file contains the `idValue` primary key, the `idEntity` foreign key, the `idAttribute` foreign key, the `idVisitation` foreign key, and the `idPatient` foreign key, in that order. Each row of the `DECIMAL.csv` file contains an empty cell for the `idValue_Decimal` primary key (to be autofilled by phpMyAdmin), the `idValue` foreign key, and the value itself (e.g. some decimal number).

As described before, these output CSV files are then used to directly import the data into the database using phpMyAdmin. This entire process proved to be an extreme challenge for the developer, due to the complexity of validating and rearranging the data to fit the prescribed relational table model. In total, this process took more time than any other phase of the project, however still significantly less time than manually uploading the data would have taken. The Python algorithm is shown below, in *appendix*.

Unrelated to the implementation of the EAV-style database model for the CARDIGAN study, there was also the `user` table that was implemented as part of the user account system. This table was imported precisely as described in *figure*. No changes were made, and the table only became populated with some data once the user account system was built and a user account was created using the `register` function, as described below in the next Section.

---

## Website Implementation

### Design Patterns

#### MVC Framework

The web application developed for this project is built upon the CodeIgniter framework, with many built-in functions to improve security of the application, and increase the efficiency of the development workflow. The core system of CI only requires a few small libraries which makes it fast, lightweight and simple, yet powerful MVC implementation.

It also has a built-in system for so-called "clean URLs" (CodeIgniter, 2019), where a URL address location within the website is mapped by the user to the function of a class that extends `CI_Controller` -- CI's implementation of the Controller component of the MVC pattern. This makes the website structure easier to understand for users, without the developer having to map urls to the application manually.

For example, this project used the in-development virtual domain `http://cardiganproject`. A default URL with no additional string is mapped to the `index` function of the `Welcome` Controller, whereas the URL `http://cardiganproject/contact` is be mapped to the `contact` function. This style of "routing" makes the URLs more optimal for search engines, easier to manually type for a user, and it looks more professional than a URL with a visible `.php` extension. Directory access via URLs is also controlled using `.htaccess` files, so that directories that only contain content such as JS, CSS and images can be accessed as intended, without URL routing.

Variables can be passed into the functions through the URL after a forward slash `/`. Routing in this way is supported by CI by updating the `$route` associative array in the `routes.php` script, which shown in *Section*.

The user then uses the custom Controller class to implement the system, and with it can request data from, or change the Model. Models in CI are extensions of the `CI_Model` class, and are used to implement various functions for retrieving or updating data in the database. They are designed to regulate any connection between the Controllers and the MySQL database connection. Connecting to the MySQL database on CI is done by configuring the `database.php` file, and is otherwise handled by the CI system -- alongside database queries. In this implementation, the CI database connection has been configured to use the `mysqli` extension.

The developer can use their Model class to manipulate the database by calling a function from it as an attribute of their Controller's superclass, e.g.:

`$data['my_data'] = $this->my_model->get_my_data();`

Which, in this example, invokes a function in the model `my_model` to retrieve an array containing data from the database.

#### Query Abstraction

Queries in CI are built using the CI Query Builder class, instead of strings of MySQL code. This class handles `mysqli` and automatically escapes any variables, although raw MySQL can still be used if the developer wishes. Using the CI Query Builder is also beneficial, because the application Model can be implemented independently from the database itself (CodeIgniter, 2019).

For example, a query that is analogous to the MySQL query:

``SELECT `user`.`Email` FROM `user` WHERE `user`.`User_Type` = 'Admin';``

Using the CI Query Builder class would produce:

``$this->db->select('Email');``

``$this->db->from('user');``

``$this->db->where(array('User_Type' => 'Admin'));``

``$query = $this->db->get();``

And then this query would be run and return an array back to the Controller using return line such as:

``return $query->result_array();``


#### Building the Web Views

The data returned from a query can then be prepared by the Controller, then passed to a template of the View as it is loaded -- again, this is done by calling the `load` class as an attribute of the custom `CI_Controller`’s superclass, as seen here:

``$this->load->view('index.html', $data);``

The View is therefore constructed by running templates in this way, for example, a view may be constructed by loading the `header.php` template, then the `index.html` content template, and then the `footer.html` template -- and the data that is passed in to each template can be controlled separately.

CI's features were used to the advantage of the developer in creating a system that followed the structure described in *Section 5.2*. This section will discuss the implementation for the Controller classes of each system, and describe the non-trivial functions of each class.


### Controllers

#### Welcome

The `Welcome` class extends `CI_Controller` and implements the simplest system within the application: the static content pages of the website, and the contact form. These static pages manifest as functions, routed to by the CI system; including:
- an `index` page and an `about` page: both of which serve to show only some static content,
- a `gallery` page: which shows all the photographs related to the study that are available in the `assets\images\photos` directory
- and a `contact` page: which contains a functional contact form, and makes use of CI's built-in `form_validation`, `session` and `email` helper libraries, and CI's `captcha` and `string` CI helper classes for additional verification, and to prevent spam.
	- How these classes are used in form validation is the same as in the `register` function of the `User` Controller class, as described later in this section.
	- After validation, the `email` class is used to construct an SMTP email which is then attempted to be sent. If it is successfully sent, a message is displayed regardless of if it was properly transferred or received.

All of these routed functions use the `static_page` function, which takes one single View template to construct the web page. The `header.php` and `footer.html` View templates are automatically added before and after the relevant template file (templates are discussed further later in this Section). This ensures that the templates that are used in each function are controlled in such a way as to map each function to a purely static page.



#### User

The `User` class extends `CI_Controller` and implements the user account system that handles user log-ins, registrations, and user account management (by the user themself, or an admin), as described in *Section 5.2*. It provides a minimal but effective system to fulfil these requirements. The source code for this class is provided in *Section*.


##### Login

The `login` function routed to by the CI system can only be accessed if the user is currently not logged in, otherwise the page redirects to the welcome page with a warning message. The form is typical of a simple login form; it has inputs for the user's email and password. When the form is submitted, the CI `form_validation` class validates the inputs against the rules declared in the function. For example:

``$this->form_validation->set_rules('email', 'Email', 'required');``

Here, the `email` user input is provided with a user-readable name `'Email'` and given the `required` rule. This means that the `form_validation` class will check that the user has submitted any text into the input bar named `email` and returns `FALSE` if they have not. If the validation returns a false flag, the login page will be loaded again, with a relevant error message.

If the validation returns `TRUE` however, another check is imposed on the submitted email, to check if the email already exists in the `user` table of the database, using a function from the `User_Model` class. If the email does in fact match against an email that already exists within the database, the `failed_login` function redirects the user back to the login page, presenting an error message.

The final level of validation required is the password. If the email matches a user in the database, the password must also match that user. The submitted password is then encrypted concatenated with the user's salt -- also stored in the `user` table -- and encrypted. Salt and encryption use the same method as in the `register` function, as explained further in this section.

If the encrypted password matches the password stored in the database, the user is successfully logged in and redirected to the `database` page of the website, and their session data is updated to include their information using the CI `session` library. Otherwise, the `failed_login` function is also executed. The user can now access any part of the website that required the `logged_in` session flag to be `TRUE`, so long as their verification level permits them to.

At any point when they are logged in, the user may use the `logout` function. This simply end their session by unsetting all user data in the session, before returning them to the `login` function with a message declaring that they have successfully logged out.


##### Register

The `register` function uses the same `form_validation` features as the `login` function, however it also makes use of the CI `captcha` and `string` libraries.

Different rules are set for the registration form, for example the `email` input is given additional rules `valid_email`, which uses the regular expression `#\A([^@]+)@(.+)\z#` to validate the email, alongside the rule `callback_check_email_exists`. This rule is a callback to the `Users` function `check_email_exists`, which declares the email to be valid only if it does not match an email that is already in the `user` table of the database.

The form also requires two inputs of the users password to set it up. This takes the form of a `password` input bar and a `password2` input bar. `password2` is given a rule that requires it to match the input of `password`. The user must then input the correct CAPTCHA, which is set up using the CI `captcha` helper class.

This class uses the GD image library (CodeIgniter.com, 2019) to automatically generates a JPEG image using a given string. The JPEG image contains the letters of that string in a strewn-about matter with a spiral background as shown in *Figure*, that a computer will find hard to read.

In this implementation, every time the `register` page is loaded and the form is not validated, a new CAPTCHA is prepared with a randomly generated 8-character alphanumerical string using the CI `string` helper class. The CAPTCHA is then generated with this string, saved to a location in the `captcha` directory, and loaded into the view with the form. The `user_captcha` input bar is also given a callback rule to the `check_captcha` function, which requires the user to input the exact same string as the one generated when the CAPTCHA was produced.

![Captcha](6-5-captcha-eg.jpg)
***Figure 11:*** *Example of a CAPTCHA image produced by the CI `captcha` helper class.*

If the user passes all these aforementioned checks and the form inputs are validated, the new user's information is stored in the database. This is done using the salt-hash method. First, a salt is generated using the PHP `random_bytes` function, with a string length of 16. Then, the salt is encoded into base64 to keep the information, but ensure that it is mostly alphanumerical -- bar the `==` at the end of the encoded string -- to prevent errors when storing into the database (The PHP Group, 2020).

The next step concatenates the salt with the submitted password, and then encrypts the result using SHA-512, a Secure Hashing Algorithm 2 function (Penard, W. and van Werkhoven, T., 2016). Both the newly encrypted password, and the salt, are passed to the `User_Model` to be added to the database. The user is then redirected to the `login` page of the website and presented with a message explaining that they have successfully registered an account. They may then log in if they wish.


##### Manage Account

The `change_password` function also uses the above-mentioned libraries to enable CAPTCHA, and form verification. This function allows a logged-in user to manage their account by giving them the option to change their password. The View that is loaded by this function also provides a link to the `delete_account` function.

This function largely follows the same processes as the `login` function and `register` function. The `password` input bar verifies the user's current password as per the `login` function, and the `password2` and `password3` input bars are verified as per the `register` function. Then there is a check to see if the current password is correct, and that the new password is not the same as the old password -- and in the case of failure, the user receives an error message.

In the case of success, where the form validated the old and new passwords, accepted the CAPTCHA, the new password is different from the old password, and the current password is correct: the new password is updated in the same way as it is originally set in the `register` function.

If the user clicks the `delete_account` hyperlink on the View for this function, and accepts the "Are you sure?" alert, the `delete_account` function simply instructs the Model to delete that user's row from the database, before logging the user out, and returning them to the `login` function, with a message declaring that their have deleted their account.


##### User List

The final non-trivial function in the `User` Controller class is the `users_list` function, which is only accessed by a user that is logged in, and has the `Admin` permission level. This function produces a View that displays all the user accounts in the database in a table. It shows every user's name, email, some metadata and their permission level.

This function can take arguments. The purpose of this function is to allow admins to manage other user account's permission levels; each row on the table provides a drop-down menu on the user's permission level, that can be changed. Each menu option of the drop-down is a permission level that the user does not have. The admin may change a user's permission level by selecting one from the drop-down menu.

This works because each option is a separate link to the `users_list` function that gives arguments for the `user_id` and the new `user_type` after forward-slashes `/`. For example, the top-most user on the list may not be verified and the admin wants to verify them to allow them to access the database view.

They can do this by selecting the drop-down menu on that top user's row, and then clicking on the `Verified` option, which links to `view_all_users/edit/1/Verified`. The URL `http://cardiganproject/view_all_users` is routed to the `users_list` function, and the URL `http://cardiganproject/users/edit/1/Verified` will pass the arguments `1` and `Verified` into the `users_list` function.

When arguments are passed into the function, the function will loop through each user until the user's database ID matches the passed-in `user_id` argument. If they match, and the user is in the database, the Model is instructed to update that user's permission level. This way, if the admin tries to change the permission level of a user that does not exists, the user will simply not be found and nothing will happen because no error will occur.



#### Database

The `Database` class extends `CI_Controller` and implements the CARDIGAN study database system. It allows any logged-in, verified user to view and download any data they require from the database. The source code for this class is provided in *Section*.


##### Form

The default function for this Controller is the `index` function, which can take four NULL-able arguments: `type`, `subtypeID`, `entityID`, and `attributeID`. It can only be accessed by verified users that are logged in. This function uses session data to construct a view that displays a series of drop down menus on the right side, and a table on the left, as described in the design in *Secion 5.3.*.

The purpose of these menus is to explore the data sets; they enable the user to search for individual data items from within each of the subsets and categories. When a data item is selected, it is added to the list of selected data items displayed in the table on the left side of the View.

The series of drop-down menus is constructed dynamically from a set of templates that are loaded into the view. Using the `update_form` function, any arguments that are passed into the `index` function determine what is available on the drop-down menus.

When the `index` function is accessed for the first time, or if the search process has been reset using the `reset_form` function (which unsets all database-related session data), the only options available to the user is the choice between the Clinical and the Gait data sets -- presented as two buttons. These buttons are hyperlinks that pass the selected data set into the `index` function, for example one links to the URL `http://cardiganproject/database/Clinical` passes the string `Clinical` into the `index` function, as the `type` argument.

The `type` argument is then passed into the `update_form` function, and any subsets associated with the Clinical data set are retrieved from the Model, and both the `type` argument, and an array of all the subsets are added to the session user-data. The `database` page is then refreshed -- rerunning the `index` function with no arguments, to reset the View -- and now that these subsets are set in the session, a drop-down menu that allows one of them to be selected is loaded into the View, in place of a `gap.html` template that was originally loaded in it's place.

This process is repeated when a subtype is selected from this first drop-down menu, but now both the data set and the subset are selected and passed into the `update_form` function. As such, the URL would be `http://cardiganproject/database/Clinical/1`, and the `subtype` argument has now been set. The subtype is then also saved into the session and the categories (referred to as *entities* in the Model) are then retrieved by the Model, and stored in the session. The View is then refreshed and a drop-down menu of categories is presented to the user.

In a similar fashion to the subtype drop-down menu, a category can now be chosen by the user and then given to the `entityID` argument of the `update_form` function. The same process occurs as before, and now chosen category is saved to the session with all it's associated data items (referred to as *attributes* by the Model), after they are retrieved from the Model.

However, it is possible that there may only be one data item within that category, in which case the search is over, and that data item is added to an array that contains all the currently chosen items (stored in the session), using the `add_to_selected` function. It is from this array that the table on the right side of the View is populated -- showing all the relevant information about each selected data item.

It is also possible that there is actually no data for any of the data items in that category, in which case nothing happens; no data items can be searched. If more than one data item is present in that category, then yet another drop-down menu will be presented to the user, allowing them to select the specific data item of interest to add to the table, as described above.


##### Results

Once there is at least one data item selected and added to the table, the user has several options. The user has the ability to remove the data item from the list, by clicking the red waste bin button. This is a hyperlink that invokes the `remove_from_selected` function. It takes two arguments: `attributeID` and `subtypeID`, and then recreates the array of selected data items, but skips the data item with those corresponding IDs. It then sets the new array in the place of the old array in the session, and refreshes the View.

The user also has the ability to download the data for a given data item in CSV format, by clicking the green document button. This invokes the `export_from_selected` function, which makes use of CI's `file` helper class, and takes in the same two arguments as `export_from_selected`, above. Firstly, it finds the selected data item from the session array, and then retrieves the data using the `get_results` function.

The `get_results` function takes an array of selected data items as an argument, and retrieves the data values corresponding to those data items for each patient from the Model, using the `get_results` function of the `EAV_Model` class. The `EAV_Model->get_results` function is an especially significant function of the Model, and returns an array containing the entire `value` table row for every value for the target data item, and all the relevant metadata for it, including the data from the row of the `patient` table, the `attribute` table, the `visitation` table and the `data_type` table. This array is returned to the `Database->get_results` function, which then significantly alters the format in which the array is arranged, from how it is received from the Model, to the function call, so that the results can be more easily navigated through in context (e.g. for the View).

The case of the `export_from_selected` function, the resulting array from the `EAV_Model->get_results` function is assigned to a value. The `export_from_selected` function then prepares to create a CSV file; it starts by setting a CSV file transfer HTTP header, and then opens an output stream. The first line of the CSV file is then written, which contains the column names. The first column contains the Patient ID, and the next eight columns contain the weekly visitations. The data values are the next to be written to the file. The results array is loops through each visitation for each patient, and writes all values for that visitation. Then the file is closed and transferred to the user.

The final option provided by the `index` function of the `Database` Controller is the `results` function, which is invoked by clicking the "View All" button when it is green. This function simply runs the `get_results` function on every selected data item in the session array, and displays all the results in a single table, on a separate view. The `result` template in the View handles the process of looping through the complicated results array that `get_results` produces.


### Content

Unfortunately, due to time constrains on the project, the optional planned content management system was not implemented, although it is understood how it could be done. It would have taken much more development time that would have taken away from the already time-consuming core aspects of the system such as database access, integrity, account management, security, and presentation of the website.

---

# Evaluation

## What successfully fulfilled the requirements?

The purpose of this project was to develop a web-based software application with a relational database solution for storing the CARDIGAN project's study data. The high-priority functional requirements and non-functional requirements were all fulfilled, and the low-priority and optional functional requirements were almost all fulfilled, bar one due to time constraints.

The developer considers that the website successfully fulfils the low-priority requirements FR8 and FR9. It functions to promote the CARDIGAN project and lets any user understand the purpose of the CARDIGAN project on the *welcome* page, and become aware of the team behind the project on the *about* page. It also allows any user to view the photographs produced during the study on the *gallery* page, and any user can contact the team using the form on the *contact* page. The entire website has been developed with tools that enable it to look professional and function responsively, so that it can be used on mobile devices and tablets, fulfilling NFR4, and has been implemented using an MVC architectural pattern, fulfilling NFR2.

The developer considers that the user account-related requirements FR1, FR2, FR3 and NFR1 are all fulfilled to an appropriate and satisfactory degree. Any user can successfully register an account, log in, change their password, or delete their account, but starts with minimum-level *unverified* privileges. The user can then use the *contact* form to contact an admin and request to grant that user access to the database function of the website. A user with *admin*-level privileges has the ability to manage the privilege levels of other users in the database, and the admin can then decide to grant *verified* privileges to that user.

The developer also considers that the log-in, registration, account management and contact form -- elements of the system that involve the users' details, such as email and/or password -- have been implemented with a moderate and appropriate level of security, and fulfil NFR1 by employing CAPTCHA tests for human user verification, and using a salt-hash method of password encryption.

The requirements FR4 and NFR3 are both considered successfully fulfilled by the database system of the application. This system successfully allows *verified* users to browse, view and download the results of the CARDIGAN study in a way that makes them potentially quite useful for the researcher to perform an analysis. The relational database was successfully implemented using an EAV-based model for flexibility and to allow storage of many heterogeneous, unrelated column names or data items.

The database was also successfully populated with the data values and their metadata, from the CARDIGAN study. This process proved to be one of the most challenging and time-consuming phases of the project, but it was extremely critical, because it was required for the database to work and fulfil the main functional requirements. It proved to be so difficult, due to the unprecedented level of complexity encountered by the developer in automating a data transformation process. The raw data needed to be transformed so that the values and their metadata (including generating primary key ID numbers) could align to the EAV model, so as to make both querying the data, and extending it in future, much easier. As such, this phase required several iterations to be successful. It then considerably overran the planned time-frame, and resulted in the low-priority requirement FR9 being scrapped, so that other aspects of the application could be given more attention in the final phases of the development process.

However, after the database was successfully populated, the implementation of the web-based system for browsing and displaying the data proved to very successful. The *verified* user can browse through each data set, subset and category to find the data item they require, and can successfully add that item to a list, fulfilling FR6. They are then given the option to successfully download all the data for any of these items in the list, or view all the data across all the selected items, fulfilling both FR5 and FR7.


## What currently needs to be improved?

There are many ways that the application could be improved. Chief among these are the issues with the database that have still not been resolved, throughout the entire implementation phase: The database's integrity is still not completely fixed -- some data values have been imported into the database incorrectly, and need to be individually fixed, or reuploaded. This is a minor task that can still be carried out before deployment. Also, no Clinical data from the fifth visitation was never translated into English. This is the result of a lack of communication between the developer and the CARDIGAN team. This still needs to be resolved, however it may be possible for the developer to use software to translate that data and any future data into English automatically. On an aesthetic note, the photo gallery could be improved to use a more dynamic, JS-based system such as a slideshow, with arrows to traverse the gallery of images. This would be better and faster to load than the current gallery implementation, because currently all the photographs load at one time, and download at full resolution.



## What could be added in the future?

### Content Management

If given another timeframe within which to maintain the application and continue it's development, there are many possible improvements and new functions that could be added in the future. Chief among those, is the functional requirement that was not able to be completed during this project's progression: a Content Management System (CMS). This system would allow any admin to add new content such as news or blog posts, scheduled events, and new gallery photos. If accomplished by development, this would require at least one new table to be added to the database to contain content such as text, dates, and timestamps. This table would likely have a relationship with the `user` table, to represent the creator of the post.

To implement this system on the website, a Model to access this new table, and a Controller would be implemented including a new View template containing a form to allow the admin to create the new blog post or event. The most recent of these posts would then be dynamically loaded into the `Welcome` page for all users, and will link to their own pages, to allow the user to view the full post.

However, the CMS application is already a very mature software segment, and t may be considered unnecessary to create it from scratch. Hence, integration of an existing open source CMS such as Wagtail, would be the recommended approach. In addition, many CMS  platforms come with much more refined and user-friendly UI, that would otherwise take up considerable development time to create something resembling competitor applications.

### CAPTCHA Integration

After deployment, the website will have an official domain. This opens the door for another improvement that would be greatly preferred -- the use of Google reCAPTCHA over the current CI `captcha` helper class. Google reCAPTCHA is a much more user-friendly CAPTCHA API that only requires the user click the "I'm not a robot" Checkbox that assesses whether the human or not based on their web-browsing behaviour. In order to implement this form of CAPTCHA in the application, the domain of the website must be provided to Google to sign up for an API Key Pair (Google reCAPTCHA, 2020).

### Security and Email Integration

Another improvement to the application this is only possible to begin work on after it is hosted on an official domain, would be to the user account system. The account system would benefit with an extra layer of security form email verification. The newly-registered user will then have a second form of verification, where the user must verify their email address to prove that they are it's owner. This is a very standard system employed by almost all web-based user account systems, but could not be included within the scope of this project.

This subsystem would be implemented by adding code to the `register` function of the `User` Controller that automatically sends an email to the newly-registered user that contains a link to a new function, with a special token, unique to that account. If the user clicks this link, they are directed to the application and the system receives that unique token, proving that the user logged into their email and clicked the link to verify it on the `User` system. The user's email address is then considered verified -- and a new flag will need to be added to the `user` table of the database to represent this.

Another way that emails could be used to the improve the system (once it is possible for the developer to start implementing them), would be to allow the user to subscribe to email updates for news or blog posts and events from the CARDIGAN team. The contact form may also be given the opportunity to email the user a confirmation that their message has been sent to a member of the CARDIGAN team.

Relevant to the user account system, another level of security and authenticity for user accounts to verify themselves could involve organising a way to allow users to log in, or synchronise their account, with their account for an official academic organisation or research body. For example, if the user is a part of a university, they may be able to link their account in the system with their official university account -- much like they would when connecting with an official digital library or database. For example, students at Oxford Brookes University can access many resources, such as the ACM Digital Library, by logging in through their university's website. A Single Sign-On (SSO) implementation such as this would improve the security and legitimacy of the users by a large amount, while also rendering obsolete the current system of requesting admins to manually verify accounts (which if removed, would make an admins' much job less cumbersome).

### Automation of Future Data Entry

Given the flexible nature of the EAV-based database model, the system could potentially continue to be used for future studies. This would require some more work, including some work on the database model itself, but the database system of the application could be developed further by allowing an admin to add more study data to the database. For example, if another visitation occurred, the system should be implemented with the features to allow an admin-level user to import that week's data into the system. This would require modifying the Python scripts to work in a more generalised fashion to verify data more robustly, and enable the PHP-based Controller to access these Python functions that verify and transfer data into a format that can be directly imported into the MySQL database using the Model.

It also follows, that the during new visitation, novel questions may be asked in the survey, or new biochemical measurements may be taken. This means that there will likely be new column titles or *data items*. This is supported naturally by the EAV-based model implemented into the system, because the so-called *data items* and *categories* are represented as rows in the `attribute` and `entity` tables, respectively. The system will need to handle any new column titles in the imported study data by generating new rows in the `attribute` and/or `entity` tables, when presented with new ones that are not already present in those tables -- alongside any other metadata, such as new visitations or new patients.

This concept could be further generalised, such that the system could be developed for use in any kind of clinical study, or at minimum another study held by the CARDIGAN team. This would require some small restructuring of the database model and a new set of `Database` Controller functions, `EAV_Model` functions and View templates -- essentially a new subsystem -- to be developed. The ultimate goal in this possible future implementation would be to allow the same users to login to access this family of databases systems, and each individual database system has separate access to it's own data sets, subsets, entities and attributes, all within the one unified database, and unified user account system.



## What would be done differently if the project was started again?

There were a number of more glaring issues that the project faced, that would not have been repeated with hindsight. The following things are what would have been done differently, given that the project was started again from scratch.

### Process

First, and most important of these, is the poor management of the project by the lead developer. Poor time management, and fairly poor communication on behalf of the developer, with the stakeholders of the project -- the CARDIGAN team. The developer did not do well enough to stick to the initially planned timeframes, and repeatedly chose to make the most headway as the project started to get late. This means that the project suffered in many ways, ultimately resulting in a low-priority requirement getting scrapped. Ideally, every single requirement discussed would be implemented, regardless of priority-level. The developer also failed to stick to regular meetings with the stakeholders, resulting in a lack of communication and hence led to the developer making decisions based on assumptions, without first consulting in depth with the stakeholders of the project.

### Implementation

A way that the implementation of the project would be done differently with hindsight, is to be more meticulous to separate the Views from the Controllers. Every template that was used to construct the views should preferably have been built entirely from only HTML (and whatever small amounts of JS), with no PHP programming inside the templates themselves, outside of references to the variables -- which don't require the `.php` extension to be recognised. This would completely decouple the Views from the Controllers, and move all the programming to the Controller, where it is clearly visible and controllable by the developer, and makes for a much cleaner implementation of MVC.

An example of how this would be done could be given in the `result` View template, used in the `Database` Controller. There is a lot of PHP programming in this template -- it contains a set of loops for searching through the results of the database search. However, this template could be split into a set of smaller templates that contain only references to a variable, and would all use only `.html` extensions. Using these templates, the looping through of the `all_results` variable would be done in the Controller, and the Controller will choose which templates to load dynamically. In the case of the table, the cells could be loaded as individual templates, using a generic `table_cell.html` template, or even by simply echoing a string of HTML code that can display whatever variable is passed into the `load->view()` function as the second argument.

### Data Schema

The most major alteration in the design that would have been made, with hindsight, is the database structure and implementation. It is of the opinion of the developer that the use of EAV has had mixed results, and is not as efficient, logical or as descriptive as it could be. In fact, it is quite possible that the use of the EAV might be unnecessary, and that a more generic "questionnaire"-style database could have been more appropriate, or at least one that possibly combined the `entity` and `attribute` tables into one, since their `Name` columns were found to be largely duplicated across both tables.

It was also found that most of the Clinical data from the Preconsultation (visitation 1) was different, and separated from, all the other data. Most of the data items of the Clinical data set and that visitation do not reoccur on other visitations, because these data items are not recorded for analysis over time, and are only there to give context for the patient. It is therefore valid to argue that "one-off" or "overall" data such as this should be treated differently from other "weekly" data that occurs on multiple visitations. For example, these data could be stored the traditional, non-EAV way inside the `patient` table, since these data are singular and specific to particular patients. If the entire dataset had been properly reviewed before the schema decision was made, this issue could have been averted.

The final main issue with the database implementation was the implementation of the `data_type` table, for which it's name, and the names of it's columns were not carefully deliberated at the time of design and implementation. This should have been given a more descriptive and intentional name such as `data_set`, and it's column names should also be reconsidered as so:

- `Type` 		→ `Set`
- `Subtype` 	→ `Subset`
- `Walk_Type` 	→ `Description`
- `Weekly` 		→ `Recurring`

All of these columns, bar `Weekly`, should not have been implemented in the database as enumerators. It would have been extremely beneficial to make each of these foreign keys, and to have had simple, separate, `set`, `subset` and `description` tables. This would have allowed for changes to be made more easily for categorising the data, and would have made the system more flexible for future data sets.

# Conclusion

Overall, the project is considered a success by the developer. Despite the issues that the database model still has, the overall application has a professional looking interface and generally functions as intended. However, the developer deeply regrets the failures in project management, timekeeping, formal testing, and communication, which caused several major hitches throughout the project process. Despite this, the developer feels that they learned a lot about proper project management and successfully developed their software development skills.
