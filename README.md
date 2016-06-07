# DOMSerialize #

Make writing and comparing small XML snippets pain-free.
Serialize and de-serialize XML DOM nodes.

```<?>
<ul <li <a @href some.url | Compact, serialized XML! >>>
```

Free, open-source BSD 2-clause licence; see 'LICENSE.txt'.


## Specification ##

Version 0.

>   Be conservative in what you do, be liberal in what you accept from others
>   ~ *Postel's Law*

*   The term **should** can be inferred to mean something that we always
    output, but do not require for input

*   The term **should not** can be inferred to mean something that we accept
    as input, but do not output

*   The term **must** can be inferred to mean something that is invalid if
    excluded from input _or_ output

*   The term **cannot** can be inferred to mean something that is invalid
    in either input _or_ output

We will always output serialized XML in a strict, reliable format with
consistent whitespace. We accept serialized XML with flexible whitespace.

*   "Whitespace" is defined as ASCII space, tab or newlines (`\r`, `\n`)
    
*   We output only single spaces for whitespace,
    but we accept any whitespace in its place
    
*   We do not begin or end a string with whitespace,
    but we do accept it


### Prologue ###

A serialized-XML string **should** begin with `<?>`.

*   We **should not** use whitespace between the angle-brackets and the `?`

Implementers may provide an option to omit the prologue, but should include
it by default as this is for the benefit of text editors and syntax
highlighting.


### Names ###

A 'name' refers to an _element name_, _namespace name_ or _attribute name_.

*   A valid name **cannot** begin with a numeral or a dash

*   A valid name contains only the ASCII letters `A`-`Z`, `0`-`9` or `-`

*   We **cannot** accept invalid names


### Elements ###

Elements begin and end with angle-brackets `< ... >`.  
We do not use _'opening'_ and _'closing'_ tags.

```<?>
<e>
```

We **should not** use whitespace between the _element name_
and the angle-brackets.


### Attributes ###

An attribute **must** begin with the "`@`" symbol,
followed immediately by the attribute name.
  
```<?>
<e @attr>
```

*   We **should** separate the _attribute name_ from the _element name_
    with whitespace

*   We **cannot** separate the "`@`" symbol and _attribute name_
    with whitespace

*   We **should not** place whitespace between the _attribute name_
    and the closing angle-bracket

Multiple _attribute names_ **should** be separated by whitespace.

```<?>
<e @one @two>
```

An optional _attribute value_ follows an _attribute name_:
  
```<?>
<a @href http://google.com >
```
*   We **must** separate the _attribute name_ and the _attribute value_
    with whitespace

*   We **should** separate an _attribute value_ and a closing angle-bracket
    with whitespace

Attribute values may contain whitespace:
  
```<?>
<e @animals cat dog rat >
```

Attribute values can contain quotes!

```
<e @attr "with-quotes!" >
```

See the [Escape Codes](#escape-codes) section for how to deal with the use of
special characters within attribute values that may conflict with
serialized-xml syntax.


### Content ###

Text that follows the _element name_ defines the textual content of the
element.

```<?>
<f The quick brown fox >
```

*   Obviously, we **must** use whitespace between the _element name_
    and the textual content

*   We **should** use whitespace between the textual content
    and the closing angle-bracket

We separate textual content (XML text nodes) from _attribute names_
with a bar:

```<?>
<e @attr | Eat at Joe's! >
```

*   The bar is considered the beginning of content,
    and is not taken to be part of the content itself

*   We **should** separate the _attribute value_ and bar with whitespace

We likewise separate textual content from _attribute values_:

```<?>
<a @href some.url | Click Here! >
```

*   We **must** separate the _attribute value_ and `|` with whitespace,
    otherwise it is taken as part of the _attribute value_. This is done
    so that URLs can use `|` without having to be escaped

See the [Escape Codes](#escape-codes) section for how to deal with
the use of special characters that may conflict with serialized-xml syntax.

Elements can be nested within textual content:

```<?>
<f the quick brown fox <d jumps over the lazy dog >>
```

Textual content may directly follow an element:

```<?>
<f The <q quick > brown fox >
```

Elements can be nested directly:

```<?>
<e <f <g> <g> <g>>>
```

*   We **should** use whitespace between _element names_, _namespaces_ or
    _attributes_ and the next opening angle-bracket

*   We **should** use whitespace between elements

*   We **should not** use whitespace between contiguous closing angle-brackets


### Comments ###

We accept comments, but we never output them
(all output is on a single line)

```<?>
<# comment goes here #>
```

*   We accept any whitespace within a comment, including none at all


### Escape Codes ###

The letters `<` and `>` **cannot** appear un-escaped in textual content.  
Additionally `@` and `|` **cannot** appear un-escaped in an _attribute value_.

-   `&<` escapes `<`
-   `&>` escapes `>`
-   `&@` escapes `@`
-   `&|` escapes `|`
-   `&&` un-escapes `&`, for writing literal `&<` etc.

For textual content that begins with an `@` or `!`, we **must** use the bar to
force the beginning of textual content and avoid interpretation as an
_attribute name_ or _namespace_:

```<?>
<twitter | @github >    <# this isn't an attribute! #>
<noise | !bang >        <# not a namespace #>
```

Lastly, for textual content that begins with `|`, use a second bar:  
(the first bar is eaten to force beginning of content)

```<?>
<bar | | | >            <# = | | #>
```

### Namespaces ###

In XML, an element may define a default namespace; e.g.

```XML
<html xmlns="http://www.w3.org/1999/xhtml">
```

We define a default namespace on an element by placing a space immediately
after the element name, then a single exclamation mark, another space,
and then the namespace's URI:
    
```<?>
<html ! http://www.w3.org/1999/xhtml >
```

*   Quotes around the URI would be accepted as part of the URI,
    so make sure to leave them out!

*   We **should** place whitespace between the URI and the closing
    angle-bracket. If the whitespace is missing, the angle-bracket
    is not taken to be part of the URI

*   We **must** use whitespace between the _element name_, `!`, and the URI

*   We **cannot** use multiple default-namespaces

We _undefine_ a default namespace with a double-exclamation mark

```<?>
<e !! >
```

*   We **should** use whitespace around the double exclamation-mark

*   We **cannot** place whitespace between the two exclamation-marks

We define an element namespace by placing the name immediately after
the exclamation mark

<!-- TODO:  Undefining prefixed namespaces; allowable in XML1.1
            https://www.w3.org/TR/xml-names11/#defaulting
-->

```<?>
<e !ns http://some/uri >
```

*   We **cannot** accept whitespace between the exclamation mark and the
    namespace name

We allow the element to be of a _namespace_ by writing the namespace first,
followed by a colon, and then the element name, such as we do in XML

```<?>
<ns:e>
```

*   We **cannot** accept whitespace between the namespace, colon,
    and element name