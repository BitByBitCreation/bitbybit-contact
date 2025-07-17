<?php

namespace App\Enums;

enum AnalysisField: string
{
    case Sentiment = 'sentiment';
    case Keywords = 'keywords';
    case Summary = 'summary';
    case ToxicityScore = 'toxicity_score';
    case SpamScore = 'spam_score';
}
