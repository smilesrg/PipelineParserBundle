# PipelineParserBundle
[WIP] Pipeline comments parser for FOSCommentBundle.

#Usage

```
    mention_parser:
        class: Smilesrg\PipelineParserBundle\Parser\UserMentionParser
        arguments:
            - @fos_user.user_manager
            - @router
            - "fos_user_profile_show"
            - "/\B\@([\w\-@]+)/im"

    pipeline_parser:
        class: Smilesrg\PipelineParserBundle\Parser\PipelineParser
        calls:
            - [addToPipeline, ["@mention_parser"]]
```
