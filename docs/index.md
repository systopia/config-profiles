# Configuration Profiles

This is a utility extension providing a generic data structure and UI for
extensions with profile-like configuration items, i. e. providing multiple sets
of configuration options, which we call *Configuration Profiles*.

Think of an extension that provides connections to an external API (or vice
versa), each with different options for processing incoming data, you might
e.&nbsp;g. want to have activities of different types created for each
connection.

Each type of configuration profiles, no matter what purpose it serves, also
shares some common pieces of information, such as an ID, a title, a date of its
last usage, etc. - and this is what this extension is providing to developers of
extensions, saving them from re-developing configuration profile data structures
over and over again.

When saving extension configuration that might grow indefinitely, using the
CiviCRM *Settings API* might also not be a good idea, as there are length limits
for setting values. You would want your own database table for configuration
profiles, which - in most cases - would look very similar across different
configurable extensions. This extension provides a generic database table for
configuration profiles of any type, serializing additional properties in a
generic `data` column.
