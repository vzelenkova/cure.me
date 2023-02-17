<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Doctor;

/**
 * DoctorSearch represents the model behind the search form of `app\models\Doctor`.
 */
class DoctorSearch extends Doctor
{
    public $full_name;
    public $email;

    public function attributes()
    {
        return array_merge(parent::attributes(), ['user.full_name', 'user.email']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['user.full_name'], 'string'],
            [['user.email'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Doctor::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'user_id' => $this->user_id,
        ]);
        $query->joinWith('user');
        $query->andFilterWhere(['LIKE', 'user.full_name', $this->getAttribute('user.full_name')]);
        $query->andFilterWhere(['LIKE', 'user.email', $this->getAttribute('user.email')]);

        return $dataProvider;
    }
}
