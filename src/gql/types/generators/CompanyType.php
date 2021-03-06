<?php

namespace percipiolondon\companymanagement\gql\types\generators;

use craft\base\Field;

use percipiolondon\companymanagement\CompanyManagement;
use percipiolondon\companymanagement\elements\Company as CompanyElement;
use percipiolondon\companymanagement\gql\interfaces\elements\Company as CompanyInterface;
use percipiolondon\companymanagement\gql\types\elements\Company as CompanyTypeElement;
use percipiolondon\companymanagement\helpers\Gql as GqlHelper;

use craft\gql\base\GeneratorInterface;
use craft\gql\GqlEntityRegistry;
use craft\gql\TypeManager;

/**
 * Class CompanyType
 *
 * @author Percipio Global Ltd. <support@percipio.london>
 * @since 1.0.0
 */

class CompanyType implements GeneratorInterface
{

    public static function generateTypes($context = null): array
    {
        $companyTypes = CompanyManagement::$plugin->companyTypes->getAllCompanyTypes();
        $gqlTypes = [];

        foreach ($companyTypes as $companyType) {
            /** @var CompanyType $companyType */
            $typeName = CompanyElement::gqlTypeNameByContext($companyType);
            $requiredContexts = CompanyElement::gqlScopesByContext($companyType);

            if (!GqlHelper::isSchemaAwareOf($requiredContexts)) {
                continue;
            }

            $contentFields = $companyType->getFields();
            $contentFieldGqlTypes = [];

            /** @var Field $contentField */
            foreach ($contentFields as $contentField) {
                $contentFieldGqlTypes[$contentField->handle] = $contentField->getContentGqlType();
            }

            $companyTypeFields = TypeManager::prepareFieldDefinitions(array_merge(CompanyInterface::getFieldDefinitions(), $contentFieldGqlTypes), $typeName);

            // Generate a type for each company type
            $gqlTypes[$typeName] = GqlEntityRegistry::getEntity($typeName) ?: GqlEntityRegistry::createEntity($typeName, new CompanyTypeElement([
                'name' => $typeName,
                'fields' => function() use ($companyTypeFields) {
                    return $companyTypeFields;
                }
            ]));
        }

        return $gqlTypes;
    }

}